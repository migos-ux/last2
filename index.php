<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_save_path(__DIR__ . '/assets/sessions');
session_start();
require_once 'config/database.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';

// Auto-load des classes
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/',
        'models/',
        'core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$router = new Router();

// Routes publiques
$router->add('', 'HomeController@index');
$router->add('products', 'ProductController@index');
$router->add('product/{id}', 'ProductController@show');
$router->add('cart', 'CartController@index');
$router->add('cart/add', 'CartController@add');
$router->add('cart/update', 'CartController@update');
$router->add('cart/remove', 'CartController@remove');
$router->add('cart/contact', 'CartController@contact');
$router->add('search', 'ProductController@search');

// Routes d'authentification
$router->add('login', 'AuthController@login');
$router->add('register', 'AuthController@register');
$router->add('logout', 'AuthController@logout');

// Routes admin
$router->add('admin', 'AdminController@index');
$router->add('admin/products', 'AdminController@products');
$router->add('admin/products/create', 'AdminController@createProduct');
$router->add('admin/products/edit/{id}', 'AdminController@editProduct');
$router->add('admin/products/delete/{id}', 'AdminController@deleteProduct');
$router->add('admin/transactions', 'AdminController@transactions');
$router->add('admin/analytics', 'AdminController@analytics');
$router->add('admin/kpis', 'AdminController@kpis');

// API Routes pour AJAX
$router->add('api/transaction/update', 'ApiController@updateTransaction');
$router->add('api/analytics/data', 'ApiController@analyticsData');

$router->dispatch();
?>