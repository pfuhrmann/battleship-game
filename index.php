<?php
session_start();

// Error reporting on
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

// Composer bootstrap
require 'vendor/autoload.php';

// Bootstrap Twig
$loader = new Twig_Loader_Filesystem('templates');
$twig = new Twig_Environment($loader);

// Router bootstrap
$router = new Phroute\RouteCollector();
$router->controller('/', new WorldStores\Test\Controllers\GameController($twig));

// Dispatch response
$dispatcher = new Phroute\Dispatcher($router);
$response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
echo $response;
