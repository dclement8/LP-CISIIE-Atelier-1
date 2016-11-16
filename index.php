<?php
session_start();

date_default_timezone_set("Europe/Paris");

require_once("conf/autoload.php");

$router = new sportnet\utils\Router();

// Création d'une route
// $router->addRoute('url', '\sportnet\control\SportnetController', 'nomDuControleur');
// $router->addRoute('/all/', '\sportnet\control\SportnetController', 'listAll');


// Route par défaut : afficher la liste des événements (vue : listEvents)
$router->addRoute('default', '\sportnet\control\SportnetController', 'listEvents');

// Afficher le détail d'un événement passé en GET ($_GET["event"]) (vue : detailEvenement)
$router->addRoute('/evenement/', '\sportnet\control\SportnetController', 'detailEvenement');

// Afficher les événements de l'organisateur connecté (vue : espaceOrganisateur)
$router->addRoute('/espace/', '\sportnet\control\SportnetController', 'evenementsOrganisateur');

// Action de connexion (form) ou de déconnexion d'un organisateur + Afficher le formulaire de connexion/inscription (vue : authentification)
$router->addRoute('/connexion/', '\sportnet\control\SportnetController', 'connexion');

// Afficher le formulaire de création/modification d'un événement passé en GET ($_GET["event"]) (vue : creerEvenement) + Action  de création/modification d'un événement (form)
$router->addRoute('/creerEvenement/', '\sportnet\control\SportnetController', 'creerEvenement');

// Action de suppression (form) d'un événement passé en GET ($_GET["event"])
$router->addRoute('/supprimerEvenement/', '\sportnet\control\SportnetController', 'supprimerEvenement');

// Afficher le formulaire de création/modification d'une épreuve passée en GET ($_GET["epreuve"]) d'un événement passé en GET ($_GET["event"]) (vue : espaceOrganisateur) + Action de création/modification d'une épreuve (form)
$router->addRoute('/creerEpreuve/', '\sportnet\control\SportnetController', 'creerEpreuve');

// Action de suppression (form) d'une épreuve passée en GET ($_GET["epreuve"])
$router->addRoute('/supprimerEpreuve/', '\sportnet\control\SportnetController', 'supprimerEpreuve');

// Action d'inscription (form) d'un organisateur
$router->addRoute('/inscrireOrganisateur/', '\sportnet\control\SportnetController', 'inscrireOrganisateur');

// Action d'inscription (form) d'un participant à une épreuve passée en GET ($_GET["epreuve"]) en rentrant son numéro de participant
$router->addRoute('/inscrireEpreuveViaNum/', '\sportnet\control\SportnetController', 'inscrireEpreuveViaNum');

// Action d'inscription (form) d'un participant à une épreuve passée en GET ($_GET["epreuve"]) en rentrant ses coordonnées
$router->addRoute('/inscrireEpreuve/', '\sportnet\control\SportnetController', 'inscrireEpreuve');

// Action de téléchargement de la liste des participants inscrits (form) à une épreuve passée en GET ($_GET["epreuve"])
$router->addRoute('/telechargerListe/', '\sportnet\control\SportnetController', 'telechargerListe');

// Action d'upload du classement (form) d'une épreuve passée en GET ($_GET["epreuve"])
$router->addRoute('/telechargerListe/', '\sportnet\control\SportnetController', 'telechargerListe');


$http_req = new sportnet\utils\HttpRequest();

sportnet\utils\Router::dispatch($http_req);