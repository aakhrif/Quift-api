<?php

use Slim\App;
use Tuupola\Middleware\CorsMiddleware;
use Dotenv\Dotenv;

return function (App $app) {
    $dotenv = Dotenv::createImmutable(__DIR__. '/../');
    $dotenv->safeLoad();
    $API_BASE_URL = $_ENV['API_BASE_URL'];

    $app->addRoutingMiddleware();

    $app->add(new CorsMiddleware([
        "origin" => [$API_BASE_URL],
        "methods" => ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
        "headers.allow" => ["Content-Type", "Authorization", "X-Requested-With"],
        "headers.expose" => [],
        "credentials" => true,
        "cache" => 0,
    ]));

    $app->addErrorMiddleware(true, true, true);
};
