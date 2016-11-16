<?php
namespace sportnet\view;

class SportnetView extends AbstractView{

    /* Constructeur
    *
    * On appelle le constructeur de la classe parent
    *
    */
    public function __construct($data){
        parent::__construct($data);
    }

    protected function method()
	{
		$htmlRender = "";

		return $htmlRender;
	}

    protected function creer() {
        $html  = "<div class='event offset-0 span-9'>\n";
        $html .= "<h3>Evénement</h3>\n";
        $html = <<<EOT
<form method="post" action="creer.html">
    <div class="event offset-0 span-9">
        <h3>Evénement</h3>
        <p><input type="text" name="nom" placeholder="Nom" required="required"></p>
        <p>
            <label>Discipline :</label>
            <select name="discipline">
                <option value="1">1</option>
            </select>
        </p>
        <div>
            Etat :
            <p>
                <label>Invisible :</label>
                <input type="radio" name="etat" value="1" checked>
            </p>
            <p>
                <label>Visible (Inscription fermées) :</label>
                <input type="radio" name="etat" value="2">
            </p>
            <p>
                <label>Visible (Inscription ouvertes) :</label>
                <input type="radio" name="etat" value="3">
            </p>
        </div>
        <p>
            <label>Date limite d''inscription :</label>
            <input type="datetime-local" name="date" required="required">
        </p>
        <p>
            <label>Tarif :</label>
            <input type="number" name="tarif" min="0" required="required"> €
        </p>
        <p>
            <label>Description :</label>
            <textarea name="description" required="required"></textarea>
        </p>
    </div>

    <div class="event offset-0 span-3">
        <h3>Ajouter une épreuve</h3>
        <p><input type="text" name="nom_epreuve" placeholder="Nom" required="required"></p>
        <p>
            <label>Date :</label>
            <input type="date" name="date_epreuve" required="required">
        </p>
        <p><input type="number" name="dist_epreuve" placeholder="Distance (en m)" min="1" max="100000" required="required"></p>
        <p><input type="submit" value="Créer"></p>
    </div>
</form>
EOT;
        return $html;
    }


    /*
     * Affiche une page HTML complète.
     *
     * En fonction du sélecteur, le contenu de la page changera.
     *
     */
    public function render($selector){
        switch($selector){
			case 'creer':
                $breadcrumb = $this->renderBreadcrumb(array('Créer un événement'));
				$main = $this->creer();
				break;

            case 'details_ouvert':
                $breadcrumb = $this->renderBreadcrumb();
    			$main = $this->method();
    			break;

            case 'details_ferme':
        		$main = $this->method();
                $breadcrumb = $this->renderBreadcrumb();
        		break;

            case 'espace':
                $breadcrumb = $this->renderBreadcrumb();
                //$main = $this->espace();
                break;

            case 'mes_events':
                $breadcrumb = $this->renderBreadcrumb();
                //$main = $this->method();
        		break;

			default:
                // Liste des events
                $breadcrumb = $this->renderBreadcrumb();
                //$main = $this->method();
				break;
        }

        $style_file = $this->app_root.'css/main.css';

        $header 	= $this->renderHeader();
        $menu   	= $this->renderMenu();

/*
 * Utilisation de la syntaxe HEREDOC pour écrire la chaine de caractère de
 * la page entière. Voir la documentation ici:
 *
 * http://php.net/manual/fr/language.types.string.php#language.types.string.syntax.heredoc
 *
 * Noter bien l'utilisation des variables dans la chaine de caractère
 *
 */
        $html = <<<EOT
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>SportNet</title>
        <link rel="stylesheet" href="${style_file}">
		<script type="text/javascript" src="../js/details.js"></script>
		<script type="text/javascript" src="../js/spoiler.js"></script>
    </head>

    <body>
        ${header}
        ${menu}
		${breadcrumb}

		<div class="container line">
			${main}
		</div>
    </body>
</html>
EOT;

    echo $html;

    }


}
