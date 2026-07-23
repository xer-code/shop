<?php

/**
 * ShopX Global — Front Controller
 * All requests are routed through this file
 */

// Error reporting based on environment
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load config
require_once __DIR__ . '/config/config.php';

// Autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    // App\Core\Router -> app/Core/Router.php
    $prefix = 'App\\';
    $baseDir = APP_PATH . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load helpers
require_once APP_PATH . '/Core/Helpers.php';

// Initialize session
App\Core\Session::init();

// Initialize router and load routes
$router = new App\Core\Router();

// Load route definitions
require_once APP_PATH . '/routes.php';

// Dispatch the request
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
// Handle language prefixes (e.g. /en/, /es/, /fr/)
$url = preg_replace('#^(en|es|fr|de|pt|it|nl|pl|sv)(/|$)#i', '', $url);
$url = trim($url, '/');

$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($url, $method);
