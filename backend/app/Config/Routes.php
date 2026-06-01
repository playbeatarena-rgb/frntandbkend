<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

// Set the default namespace for controllers
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/**
 * API Routes
 * All API endpoints are grouped under /api
 */
$routes->group('api', static function ($routes) {
    /**
     * Products API - RESTful resource controller
     */
    $routes->resource('products', ['controller' => '\App\Controllers\Api\ProductController']);
    
    /**
     * Products by category endpoint
     */
    $routes->get('products/category/(:segment)', '\App\Controllers\Api\ProductController::getByCategory/$1');

    /**
     * Categories API - RESTful resource controller
     */
    $routes->resource('categories', ['controller' => '\App\Controllers\Api\CategoryController']);

    /**
     * Cart API (future implementation)
     */
    // $routes->group('cart', function ($routes) {
    //     $routes->post('add', 'CartController::add');
    //     $routes->post('remove', 'CartController::remove');
    //     $routes->get('/', 'CartController::getCart');
    // });

    /**
     * Orders API (future implementation)
     */
    // $routes->group('orders', function ($routes) {
    //     $routes->post('/', 'OrderController::create');
    //     $routes->get('(:num)', 'OrderController::show/$1');
    //     $routes->get('/', 'OrderController::index');
    // });
});

/**
 * Default routes
 */
$routes->get('/', 'Home::index');

/**
 * Catch-all route for 404 errors
 */
$routes->match(['get', 'post', 'put', 'delete', 'patch'], '(:any)', static function () {
    throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
});
