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
		$html .= "\t<a href='/'>SportNet</a>\n";
		$html .= "</header>\n";
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
		$html .= "\t\t<li><a href='/'>Accueil</a></li>\n";

		if($breadcrumb !== null) {
			foreach($breadcrumb as $elem)
				$html .= "\t\t<li>$elem</li>\n";
		}

		$html .= "\t</ul>\n";
		$html .= "</div>\n";
		return $html;
	}


    /*
     * Affiche une page HTML complète.
     *
     * A definir dans les classe concrètes
     *
     */
    abstract public function render($selector);



}
