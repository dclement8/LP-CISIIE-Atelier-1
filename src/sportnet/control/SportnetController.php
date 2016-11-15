<?php
namespace sportnet\control;
class SportnetController {

    /* Attribut pour stocker l'objet HttpRequest */ 
    private $request=null; 
    
    public function __construct(\sportnet\utils\HttpRequest $http_req){
        $this->request = $http_req ;
    }
	

    public function listEvents(){
        
		
		//$view = new \sportnet\view\SportnetView($data);
		//$view->render('selecteur');
    }
}