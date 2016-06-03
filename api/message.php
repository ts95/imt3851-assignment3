<?php

require_once __DIR__ . '/../tools/Tools.php';

$this->respond('GET', '/exchange', function($request, $response, $service, $app) {
    if (!$app->auth->isLoggedIn()) {
        $response->json([
            'ok' => false,
            'message' => "Not logged in.",
        ]);
        return;
    }

    $exchanges = $app->db
        ->from('exchange')
        ->where('user_id', $app->auth->user()->id)
        ->select(null)
        ->select('DISTINCT id')
        ->fetchAll();

    $exchangeList = [];

    foreach ($exchanges as $exchange) {
        $message = $app->db
            ->from('message')
            ->join('safe_user ON safe_user.id = message.user_id AND message.exchange_id = ?', $exchange->id)
            ->orderBy('message.id DESC')
            ->limit(1)
            ->select('safe_user.full_name AS name, safe_user.email')
            ->fetch();

        $members = $app->db
            ->from('exchange')
            ->join('safe_user ON exchange.user_id = safe_user.id AND exchange.id = ?', $exchange->id)
            ->select(null)
            ->select('safe_user.full_name AS name')
            ->fetchAll();

        $exchangeList[] = [
            'id' => $exchange->id,
            'message' => $message,
            'members' => array_map(function($member) { return $member->name; }, $members),
        ];
    }

    $response->json([
        'ok' => true,
        'exchanges' => $exchangeList,
    ]);
});

$this->respond('POST', '/exchange', function($request, $response, $service, $app) {
    if (!$app->auth->isLoggedIn()) {
        $response->json([
            'ok' => false,
            'message' => "Not logged in.",
        ]);
        return;
    }

    $maxId = $app->db
        ->from('exchange')
        ->select(null)
        ->select('MAX(id) AS maxId')
        ->fetch()
        ->maxId;

    $newId = $maxId + 1;

    $userIds = array_merge([$app->auth->user()->id], $_POST['ids']);

    // Reuse an existing exchange if it exists
    $in = join(',', array_map(function($userId) { return (integer) $userId; }, $userIds));

    $exchange = $app->db
        ->from('exchange')
        ->where("user_id IN ($in)")
        ->limit(1)
        ->select(null)
        ->select('id')
        ->fetch();

    if ($exchange) {
        $response->json([
            'ok' => true,
            'id' => $exchange->id,
        ]);
    } else {
        foreach ($userIds as $userId) {
            $app->db->insertInto('exchange', [
                'id' => $newId,
                'user_id' => $userId,
            ])->execute();
        }

        $response->json([
            'ok' => true,
            'id' => $newId,
        ]);
    }
});

$this->respond('GET', '/exchange/[i:id]', function($request, $response, $service, $app) {
    if (!$app->auth->isLoggedIn()) {
        $response->json([
            'ok' => false,
            'message' => "Not logged in.",
        ]);
        return;
    }

    $exchange = $app->db
        ->from('exchange')
        ->where('user_id', $app->auth->user()->id)
        ->where('id', $request->id)
        ->fetch();

    if (!$exchange) {
        $response->json([
            'ok' => false,
            'message' => "You are not a part of the exchange.",
        ]);
        return;
    }

    $messages = $app->db
        ->from('message')
        ->innerJoin('safe_user ON safe_user.id = message.user_id AND message.exchange_id = ?', $request->id)
        ->orderBy('message.id ASC')
        ->select('safe_user.full_name AS name, safe_user.email')
        ->fetchAll();

    $response->json([
        'ok' => true,
        'messages' => $messages,
    ]);
});

$this->respond('POST', '/send', function($request, $response, $service, $app) {
    if (!$app->auth->isLoggedIn()) {
        $response->json([
            'ok' => false,
            'message' => "Not logged in.",
        ]);
        return;
    }

    $validator = new \Tools\Validator($_POST);

    $validator->validate('exchangeId', function($exchangeId, $params, $fail) use($app) {
        $exchange = $app->db
            ->from('exchange')
            ->where('user_id', $app->auth->user()->id)
            ->where('id', $exchangeId)
            ->fetch();

        if (!$exchange)
            $fail("You are not a part of this exchange.");
    });

    $validator->validate('message', function($message, $params, $fail) {
        if (!$message)
            $fail("No message.");

        if (mb_strlen($message) > 2000)
            $fail("Max message length is 2000.");
    });

    if ($validator->hasErrors()) {
        $response->json([
            'ok' => false,
            'errors' => $validator->getErrors(),
        ]);
        return;
    }

    $messageId = $app->db->insertInto('message', [
        'user_id' => $app->auth->user()->id,
        'exchange_id' => $validator->getParam('exchangeId'),
        'message' => $validator->getParam('message'),
    ])->execute();

    $response->json([
        'ok' => true,
        'messageId' => $messageId,
    ]);
});
