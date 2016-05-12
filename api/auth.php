<?php

$this->respond('GET', '/[i:id]', function ($request, $response, $service, $app) {
    $user = $app->db->from('user', $request->id)->fetch();
    $response->json($user);
});
