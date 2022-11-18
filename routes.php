<?php
/** @var \Bramus\Router\Router $router */

use App\Helpers\Misc;

$router->get('/', 'HomeController@get');

$router->get('/about', function () {
    Misc::plates('about');
});

$router->get('/howto', function () {
    Misc::plates('howto');
});

$router->get('/terms', function () {
    Misc::plates('terms');
});

$router->get('/stream', 'StreamController@get');

$router->mount('/admin', function () use ($router) {
    $router->get('/login', 'AdminController@loginGet');
    $router->post('/login', 'AdminController@loginPost');
    $router->get('/logout', 'AdminController@logout');
    $router->get('/', 'AdminController@dashboard');
    $router->get('/approve', 'AdminController@approve');
    $router->get('/block', 'AdminController@blockGet');
    $router->post('/block', 'AdminController@blockPost');
    $router->get('/delete', 'AdminController@delete');
});

$router->mount('/webhook/twitter', function () use ($router) {
    $router->get('/', 'HookController@get');
    $router->post('/', 'HookController@post');
});
