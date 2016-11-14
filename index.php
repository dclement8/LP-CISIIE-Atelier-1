<?php
session_start();

require_once("conf/autoload.php");

$router = new sportnet\utils\Router();

// Création d'une route
// $router->addRoute('url', '\sportnet\control\SportnetController', 'nomDuControleur');
// $router->addRoute('/all/', '\sportnet\control\SportnetController', 'listAll');

$router->addRoute('default', '\sportnet\control\SportnetController', 'listEvents');

$http_req = new sportnet\utils\HttpRequest();

sportnet\utils\Router::dispatch($http_req);