<?php
namespace sportnet\view;
abstract class AbstractView {


    protected $app_root = null;    /* répertoire racine de l'application */
    protected $script_name = null; /* le chemin vers le script */
    protected $data = null ;       /* une page ou un tableau de page */

    /* Constructeur
    *
    * Prend en paramètre une variable (un objet page ou un tableau de page)
    *
    * - Stocke la variable dans l'attribut $data
    * - Recupérer la racine de l'application depuis un objet HttpRequest,
    *   pour construire les URL des liens  et des actions des formulaires
    *   et le nom du script pour les stocker et les attributs
    *   $app_root et $script_name
    *
    */
    public function __construct($data){
        $this->data = $data;

        $http = new \sportnet\utils\HttpRequest();
        $this->script_name  = $http->script_name;
        $this->app_root     = $http->getRoot();
    }

    public function __get($attr_name) {
        if (property_exists( $this, $attr_name))
            return $this->$attr_name;
        $emess = __CLASS__ . ": unknown member $attr_name (__get)";
        throw new \Exception($emess);
    }

    public function __set($attr_name, $attr_val) {
        if (property_exists($this , $attr_name))
            $this->$attr_name=$attr_val;
        else{
            $emess = __CLASS__ . ": unknown member $attr_name (__set)";
            throw new \Exception($emess);
        }
    }

    public function __toString(){
        $prop = get_object_vars ($this);
        $str = "";
        foreach ($prop as $name => $val){
            if( !is_array($val) )
                $str .= "$name : $val <br> ";
            else
                $str .= "$name :". print_r($val, TRUE)."<br>";
        }
        return $str;
    }


    /*
     *  Crée le fragment HTML de l'entête
     *
     */
    protected function renderHeader(){
        $html  = "<header class='entete'>\n";
		$html .= "\t<a href='".$this->script_name."'>SportNet</a>\n";
		$html .= "</header>\n";
        return $html;
    }
	
	
	/*
     *  Crée le fragment HTML du footer
     *
     */
    protected function renderFooter(){
        $html  = "<footer>\n";
		$html .= "\tAtelier LP CISIIE ; 2016 ; DALICHAMPT Thibaut - PIGUET Charles - HAPPE Hugo - CLEMENT Dylan\n";
		$html .= "</footer>\n";
        return $html;
    }


    /*
     * Crée le fragment HTML du menu
     *
     */
    protected function renderMenu(){
        $html  = "<nav class='menu'>\n";
        $html .= "\t<ul class='navbar'>\n";

		$auth = new \sportnet\utils\Authentification();
		if($auth->logged_in == false)
		{
			$html .= "\t\t<li><a href='".$this->script_name."/connexion/'>Espace organisation</a></li>\n";
		}
		else
		{
			$html .= "\t\t<li><a href='".$this->script_name."/creerEvenement/'>Créer un événement</a></li>\n";
			$html .= "\t\t<li><a href='".$this->script_name."/espace/'>Mes événements</a></li>\n";
			$html .= "\t\t<li><a href='".$this->script_name."/connexion/'>Déconnexion</a></li>\n";
		}

        $html .= "\t</ul>\n";
		$html .= "</nav>\n";
        return $html;
    }

	protected function renderBreadcrumb($breadcrumb = null) {
		$html  = "<div>\n";
		$html .= "\t<ul class='breadcrumb'>\n";
		$html .= "\t\t<li><a href='".$this->script_name."'>Accueil</a></li>\n";

		if($breadcrumb !== null) {
			foreach($breadcrumb as $elem)
				$html .= "\t\t<li><a href='".$this->script_name.$elem[1]."'>".$elem[0]."</a></li>\n";
		}

		$html .= "\t</ul>\n";
		$html .= "</div>\n";
		return $html;
	}

    /*
     * Retourne différents messages d'erreurs
     *
     */
    protected function renderMessage(){
        $html = "";
        if(isset($_SESSION['message']))
		{
            switch ($_SESSION['message'][0])
			{
				case 0:
					$html = "<div class='alert'>";
					break;

				case 1:
					$html = "<div class='alert alert-success'>";
					break;

				case 2:
					$html = "<div class='alert alert-info'>";
					break;

				case 3:
					$html = "<div class='alert alert-avert'>";
					break;

				case 4:
					$html = "<div class='alert alert-danger'>";
					break;

				default:
					$html = "<div class='alert'>";
					break;
			}
			$html .= $_SESSION['message'][1] ."</div>";
			unset($_SESSION['message']);
			return $html;
        }
    }

    /*
     * Affiche une page HTML complète.
     *
     * A definir dans les classe concrètes
     *
     */
    abstract public function render($selector);



}
