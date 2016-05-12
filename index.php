<?php

session_start();

date_default_timezone_set('UTC');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/tools/Tools.php';

$klein = new \Klein\Klein();

$klein->respond(function($request, $response, $service, $app) use ($klein) {
    $app->auth = \Tools\Auth::getSingleton();

    // Instantiate database object lazily upon each request.
    $app->register('db', function() {
        return new FluentPDO(new \Tools\CustomPDO());
    });
});

$klein->respond(function($request, $response, $service, $app) use ($klein) {
    $service->render('layout.php', [
        'global' => [
            'auth' => $app->auth->user(),
        ],
    ]);
});

foreach (glob('api/*.php') as $filename) {
    $klein->with('/' . pathinfo($filename, PATHINFO_FILENAME), "$filename");
}

$klein->dispatch();
