<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;
use App\Controllers\News;
use App\Controllers\Cars;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::index');

$routes->get('news/add', [News::class, 'add']);
$routes->get('news/(:segment)', [News::class, 'single_new']);

$routes->post('news', [News::class, 'upload_new']);

$routes->get('cars', [Cars::class, 'index']);
$routes->get('cars/search/(:segment)', [Cars::class, 'searchById']);
$routes->get('cars/delete/(:segment)', [Cars::class, 'deleteById']);

$routes->get('pages', [Pages::class, 'index']);
$routes->get('(:segment)', [Pages::class, 'custom_view']);
