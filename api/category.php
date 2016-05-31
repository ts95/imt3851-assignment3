<?php

$this->respond('GET', '/?', function($request, $response, $service, $app) {
    $categories = $app->db->from('category')->fetchAll();
    $response->json([
        'categories' => $categories,
    ]);
});
