<?php
session_start();

date_default_timezone_set("Europe/Paris");

require_once("conf/autoload.php");

$router = new sportnet\utils\Router();

// Création d'une route
// $router->addRoute('url', '\sportnet\control\SportnetController', 'nomDuControleur');
// $router->addRoute('/all/', '\sportnet\control\SportnetController', 'listAll');

$router->addRoute('default', '\sportnet\control\SportnetController', 'listEvents');
$router->addRoute('/evenement/', '\sportnet\control\SportnetController', 'detailEvenement');
$router->addRoute('/espace/', '\sportnet\control\SportnetController', 'evenementsOrganisateur');
$router->addRoute('/connexion/', '\sportnet\control\SportnetController', 'connexion');
$router->addRoute('/creerEvenement/', '\sportnet\control\SportnetController', 'creerEvenement');
$router->addRoute('/supprimerEvenement/', '\sportnet\control\SportnetController', 'supprimerEvenement');
$router->addRoute('/creerEpreuve/', '\sportnet\control\SportnetController', 'creerEpreuve');
$router->addRoute('/supprimerEpreuve/', '\sportnet\control\SportnetController', 'supprimerEpreuve');
$router->addRoute('/inscrireOrganisateur/', '\sportnet\control\SportnetController', 'inscrireOrganisateur');
$router->addRoute('/inscrireEpreuveViaNum/', '\sportnet\control\SportnetController', 'inscrireEpreuveViaNum');
$router->addRoute('/inscrireEpreuve/', '\sportnet\control\SportnetController', 'inscrireEpreuve');

$http_req = new sportnet\utils\HttpRequest();

sportnet\utils\Router::dispatch($http_req);