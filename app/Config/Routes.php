<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'UserController::loginPage');
$routes->get('register', 'UserController::registerPage');
$routes->get('welcome', 'UserController::welcomePage');

$routes->post('users', 'UserController::create');
$routes->post('api/login', 'UserController::login');
$routes->group('api', ['filter' => 'jwt'], function ($routes) {
    $routes->get('profile', 'ProfileController::index');
});

