<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap.php';

session_start();

// ROUTER
$router = new Bramus\Router\Router();
$router->setNamespace('\App\Controllers');

require __DIR__ . '/../routes.php';

$router->run();
