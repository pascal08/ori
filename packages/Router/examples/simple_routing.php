<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pascal\Router;

$router = new Router;
$router->bind('GET', '/', function() {
    echo 'Hello World!';
});

echo $router->run();