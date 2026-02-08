<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/welcome', 'Welcome::index');
$routes->get('/', 'Home::index');
$routes->get('login', 'UserController::loginPage');

$routes->post('users', 'UserController::create');
$routes->post('api/login', 'UserController::login');
$routes->group('api', ['filter' => 'jwt'], function ($routes) {
    $routes->get('profile', 'ProfileController::index');
});

