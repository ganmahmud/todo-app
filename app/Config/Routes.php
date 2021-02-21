<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('TodoController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->add('/', 'Home::index');
$routes->get('/todos', 'TodoController::index');
$routes->get('todo/(:segment)', 'TodoController::todo/$1');
$routes->post('todo/create', 'TodoController::create');
$routes->put('todo/(:segment)', 'TodoController::update/$1');
$routes->delete('todo/(:segment)', 'TodoController::delete/$1');
$routes->options('(:any)', 'TodoController::options');
// $routes->resource('todo');
// Equivalent to the following:
// $routes->get('/', 'TodoController::index');
// $routes->get('/todos', 'TodoController::index');
// $routes->get('todo/(:segment)/edit', 'TodoController::edit/$1');
// $routes->patch('todo/(:segment)', 'TodoController::update/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
