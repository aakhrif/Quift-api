<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

(require __DIR__ . '/middleware.php')($app);

(require __DIR__ . '/graphql.php')($app);

(require __DIR__ . '/../routes/login.php')($app);

(require __DIR__ . '/../routes/root.php')($app);

return $app;


