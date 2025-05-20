<?php

use Slim\App;
use Tuupola\Middleware\CorsMiddleware;

return function (App $app) {
    $app->addRoutingMiddleware();

    $app->add(new CorsMiddleware([
        "origin" => ["http://localhost:3000"],
        "methods" => ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
        "headers.allow" => ["Content-Type", "Authorization", "X-Requested-With"],
        "headers.expose" => [],
        "credentials" => true,
        "cache" => 0,
    ]));

    $app->addErrorMiddleware(true, true, true);
};
