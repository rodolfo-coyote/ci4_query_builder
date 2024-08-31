<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;
use App\Controllers\News;
use App\Controllers\Cars;
use App\Controllers\Login;
use App\Controllers\RedisAuth;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::index');

$routes->get('news', [News::class, 'index']);
$routes->get('news/add', [News::class, 'add']);
$routes->get('news/(:segment)', [News::class, 'single_new']);
$routes->post('news', [News::class, 'upload_new']);


$routes->get('cars', [Cars::class, 'index']);
$routes->get('cars/(:num)', [Cars::class, 'searchById']);
$routes->post('cars', [Cars::class, 'addNew']);
$routes->delete('cars/(:num)', [Cars::class, 'deleteById']);

$routes->get('pages', [Pages::class, 'index']);
//$routes->get('(:segment)', [Pages::class, 'custom_view']);


$routes->group("api", function ($routes) {
    $routes->post("login", [Login::class, 'index']);
    $routes->get("redisauth", [RedisAuth::class, 'index']);
});
