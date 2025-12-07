<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Main');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

$routes->get('/', 'Main::index');

$routes->get('register', 'RegisterController::index');
$routes->post('register/store', 'RegisterController::store');

$routes->get('login', 'LoginController::index');
$routes->post('login/auth', 'LoginController::auth');
$routes->get('logout', 'LoginController::logout');

$routes->get('tasks', 'TaskController::index');
$routes->get('tasks/events', 'TaskController::eventsJson');
$routes->post('tasks/store', 'TaskController::store');
$routes->post('tasks/(:num)/status', 'TaskController::updateStatus/$1');
$routes->post('tasks/(:num)/delete', 'TaskController::delete/$1');


