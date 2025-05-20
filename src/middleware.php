<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Psr7\Response as SlimResponse;
use Dotenv\Dotenv;

return function (App $app) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->safeLoad();

    $allowedOrigins = [
        $_ENV['ORIGIN_URL'],
        // weitere erlaubte Origins
    ];

    $app->addRoutingMiddleware();

    // Hier $allowedOrigins per 'use' einbinden:
    $app->add(function (Request $request, Handler $handler) use ($allowedOrigins): Response {
        $origin = $request->getHeaderLine('Origin');
        error_log("[CORS] Origin: " . $origin);

        $allowOrigin = in_array($origin, $allowedOrigins) ? $origin : '';
        error_log("[CORS] Allowed Origin: " . ($allowOrigin ?: 'NONE'));

        if ($request->getMethod() === 'OPTIONS') {
            error_log("[CORS] OPTIONS preflight detected, returning 200");
            $response = new SlimResponse(200);
        } else {
            error_log("[CORS] Handling actual request method: " . $request->getMethod());
            $response = $handler->handle($request);
        }

        if ($allowOrigin) {
            error_log("[CORS] Setting CORS headers");
            return $response
                ->withHeader('Access-Control-Allow-Origin', $allowOrigin)
                ->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->withHeader('Access-Control-Allow-Credentials', 'true');
        } else {
            error_log("[CORS] No CORS headers set, origin not allowed");
        }

        return $response;
    });

    $app->addErrorMiddleware(true, true, true);
};
