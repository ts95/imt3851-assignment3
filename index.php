<?php

session_start();

date_default_timezone_set('UTC');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/tools/Tools.php';

$klein = new \Klein\Klein();

$klein->respond(function($request, $response, $service, $app) use ($klein) {
    $app->auth = \Tools\Auth::getSingleton();
    $app->db = new FluentPDO(new \Tools\CustomPDO());
});

foreach (glob('api/*.php') as $filename) {
    $klein->with('/a/' . pathinfo($filename, PATHINFO_FILENAME), __DIR__ . '/' . $filename);
}

$klein->respond('GET', '!@^/a/', function($request, $response, $service, $app) use ($klein) {
    $service->render('layout.php', [
        'global' => [
            'auth' => $app->auth->user(),
        ],
    ]);
});

$klein->dispatch();
