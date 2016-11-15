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

    protected function method(){


		return $htmlRender;
	}


    /*
     * Affiche une page HTML complète.
     *
     * En fonction du sélecteur, le contenu de la page changera.
     *
     */
    public function render($selector){
        switch($selector){
			case 'view':
				$main = $this->method();
				break;

			default:
				$main = $this->method();
				break;
        }

        $style_file = $this->app_root.'css/style.css';

        $header 	= $this->renderHeader();
        $menu   	= $this->renderMenu();
		$breadcrumb = $this->renderBreadcrumb();


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
