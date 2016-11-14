<?php
namespace sportnet\utils;
abstract class AbstractRouter {

    /*
     * Attribut Statique qui stocke les routes possibles de l'application 
     * 
     * - Une route est représentée par un tableau :
     *       [ le controlleur, la methode, niveau requis ]
     * 
     * - Chaque route est stokèe dans le tableau $route sous la clé qui est son
     *   URL (voir example en bas de ce fichier ) 
     * 
     */
    
    public static $routes = array ();

    /* 
     * Méthode addRoute : ajoute une route a la liste des route 
     *
     * Paramètres :
     *
     * - $url (String)  : l'url de la route
     * - $ctrl (String) : le nom de la classe du Contrôleur 
     * - $mth (String)  : le nom de la méthode qui réalise la fonctionalité 
     *                     de la route
     * - $level (Integer) : le niveau d'accès nécessaire pour la fonctionnalité
     * 
     * Algorithme :
     *
     * - Ajouter le tablau [ $ctrl, $mth, $level ] au tableau $this->route 
     *   sous la clé $url
     *
     */
    
    abstract public function addRoute($url, $ctrl, $mth);

    /*
     * Méthode dispatch : execute une route en fonction de la requête 
     *
     * Paramètre :
     *  
     * - $http_request (HttpRequest) : Une instance de la classe HttpRequest
     *
     * Algorythme :
     *
     * - Si l'attribut $path_info existe dans $http_request
     *   ET si une route existe dans le tableau $route sous le nom $path_info
     *     - créer une instance du controleur de la route
     *     - exécuter la méthode de la route 
     * - sinon 
     *    - exécuter la route par défaut : 
     *        - créer une instance du controleur de la route par défault
     *        - exécuter la méthode de la route par défault
     * 
     */
    
    abstract public static function dispatch(HttpRequest $http_request);

}
    
    /* 
       Après l'ajout de toutes les routes, le tableau $route ressemblera à ceci :

Array
(
    [/wiki/all/] => Array
        (
            [0] => \wikiapp\control\WikiController
            [1] => listAll
            [2] => -100
        )

    [/wiki/view/] => Array
        (
            [0] => \wikiapp\control\WikiController
            [1] => viewPage
            [2] => -100
        )

    [default] => Array
        (
            [0] => \wikiapp\control\WikiController
            [1] => listAll
            [2] => -100
        )

)
     */
