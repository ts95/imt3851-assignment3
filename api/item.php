<?php

require_once __DIR__ . '/../tools/Tools.php';

$this->respond('GET', '/[i:id]', function($request, $response, $service, $app) {
    $item = $app->db
        ->from('item', $request->id)
        ->leftJoin('user')
        ->leftJoin('category')
        ->select('user.full_name AS giver')
        ->select('category.name AS category_name')
        ->fetch();

    if ($item) {
        $response->json([
            'ok' => true,
            'item' => $item,
        ]);
    } else {
        $response->json([
            'ok' => false,
        ]);
    }
});

$this->respond('GET', '/[i:id]/images', function($request, $response, $service, $app) {
    $images = $app->db
        ->from('item_image')
        ->where('item_id', $request->id)
        ->orderBy('position DESC')
        ->fetchAll();

    $response->json([
        'images' => $images,
    ]);
});

$this->respond('GET', '/category/[i:id]/recent', function($request, $response, $service, $app) {
    $items = $app->db
        ->from('item')
        ->leftJoin('user')
        ->leftJoin('item_image ON item_image.item_id = item.id AND item_image.position = 0')
        ->join('category ON category.id = item.category_id AND category.id = ?', $request->id)
        ->orderBy('item.created_at ASC')
        ->select('user.full_name AS giver')
        ->select('item_image.filename AS filename')
        ->select('category.name AS category_name')
        ->limit(10)
        ->fetchAll();

    $response->json([
        'items' => $items,
    ]);
});

$this->respond('POST', '/new', function($request, $response, $service, $app) {
    if (!$app->auth->isLoggedIn()) {
        $response->json([
            'ok' => false,
            'message' => "Not logged in.",
        ]);
        return;
    }

    $validator = new \Tools\Validator($_POST, $_FILES);

    $validator->validate('title', function($title, $params, $fail) {
        if (mb_strlen($title) > 50)
            $fail("Maximum title length is 50 characters.");

        if (mb_strlen($title) < 5)
            $fail("Minimum title length is 5 characters.");
    });

    $validator->validate('description', function($description, $params, $fail) {
        if (mb_strlen($description) > 5000)
            $fail("Maximum description length is 5000 characters.");
    });

    $validator->validate('category', function($categoryId, $params, $fail) use($app) {
        if (!$app->db->from('category', $categoryId)->fetch())
            $fail("Invalid category ID.");
    });

    $validator->validate('images', function($images, $params, $fail) {
        if (count($images) === 0)
            $fail("An item requires at least one image. You can, however, upload multiple.");

        if (count($images) > 10) {
            $fail("Maximum number of images is 10.");
        }

        foreach ($images as $index => $image) {
            if (!in_array($image['type'], ['image/jpeg', 'image/png']))
                $fail("Image #$index is not of type jpeg or png.");

            if ($image['error'] !== 0)
                $fail("An error occured with image #$index.");
        }
    });

    if ($validator->hasErrors()) {
        $response->json([
            'ok' => false,
            'errors' => $validator->getErrors(),
        ]);
        return;
    }

    $itemId = $app->db->insertInto('item', [
        'user_id' => $app->auth->user()->id,
        'category_id' => $validator->getParam('category'),
        'title' => $validator->getParam('title'),
        'description' => $validator->getParam('description'),
    ])->execute();

    $imgDir = __DIR__ . '/../public/item/img';

    foreach ($validator->getParam('images') as $index => $image) {
        $ext = $image['type'] === 'image/png' ? 'png' : 'jpg';
        $filename = \Tools\Helper::randomFilename($ext);

        move_uploaded_file($image['tmp_name'], "$imgDir/$filename");

        $app->db->insertInto('item_image', [
            'item_id' => $itemId,
            'filename' => $filename,
            'position' => $index,
        ])->execute();
    }

    $response->json([
        'ok' => true,
        'itemId' => $itemId,
    ]);
});
