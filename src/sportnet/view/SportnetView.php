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
		// $data contient les disciplines
        $html = <<<EOT
<form method="post" action="creer.html">
    <div class="event offset-0 span-9">
        <h3>Evénement</h3>
        <p><input type="text" name="nom" placeholder="Nom" required="required"></p>
        <p>
            <label>Discipline :</label>
            <select name="discipline">
EOT;

		foreach($this->data as $discipline) {
			$html .= "\t\t\t\t<option value='".$discipline->id."'>".$discipline->nom."</option>\n";
		}

		$html .= <<<EOT
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

	protected function authentification() {
		$html = <<<EOT
<div class="bloc offset-0 span-6">
	<form method="post" action="espace_organisation.html">
		<h3>Connexion</h3>
			<p><input type="text" name="login" placeholder="Login" required="required"></p>
			<p><input type="password" name="mdp" placeholder="Mot de passe" required="required"></p>
			<p><input type="submit" value="Connexion"></p>
	</form>
</div>

<div class="bloc offset-0 span-6">
	<form method="post" action="espace_organisation.html">
		<h3>Inscription</h3>

		<p><input type="text" name="login" placeholder="Login" required="required"></p>
		<p><input type="password" name="mdp" placeholder="Mot de passe" required="required"></p>
		<p><input type="password" name="mdp2" placeholder="Confirmation" required="required"></p>
		<p><input type="text" name="nom" placeholder="Nom" required="required"></p>
		<p><input type="text" name="prenom" placeholder="Prénom" required="required"></p>
		<p><input type="text" name="adresse" placeholder="Adresse" required="required"></p>
		<p><input type="text" name="ville" placeholder="Ville" required="required"></p>
		<p><input type="text" name="cp" placeholder="Code Postal" required="required"></p>
		<p><input type="tel" name="tel" placeholder="Téléphone" required="required"></p>
		<p><input type="submit" value="Inscription"></p>
	</form>
</div>
EOT;
		return $html;
	}

	protected function listEvents() {
		// $data contient tous les événements
		$html = '';

		foreach($this->data as $event) {
			$description = $event->description;
			if(strlen($description) > 1000)
				$description = substr($description, 0, 1000).' [...]';

			$html .= <<<EOT
<div class="event offset-0 span-3">
	<h3>{$event->nom}</h3>

	<p>${description}</p>
	<p>Le {$event->dateheureLimiteInscription}</p>
	<h4><a href="details.html">≡ En savoir plus</a></h4>
</div>
EOT;
		}

		return $html;
	}

	protected function detail() {
		// $data contient un événement et son/ses épreuve(s)
		$html = <<<EOT
<div class="event large">
	<h6>Partager : <input type="text" id="partager" size="64"></h6>
	<p>Début le {$this->data->dateheureLimiteInscription}</p>
	<hr>
	<p>{$this->data->description}</p>
EOT;

		if($this->data->etat == 3) { // à ajouter dans le if : vérifier DateTime
			$inscriptions_ouvertes = true;
			$html .= "\t<div class='alert alert-success'>Les inscriptions sont ouvertes</div>\n";
		}
		else {
			$inscriptions_ouvertes = false;
			$html .= "\t<div class='alert alert-danger'>Les inscriptions sont fermées</div>\n";
		}

		// Récupérer épreuves
		foreach($this->data->getEpreuves() as $epreuve) {
			$html .= <<<EOT
<div class="epreuve offset-0 span-3">
	<h4>{$epreuve->nom}</h4>
	<ul>
		<li>{$epreuve->dateheure}</li>
		<li>{$epreuve->distance}m</li>
	</ul>
EOT;
			// L'affichage est différent si les Inscriptions sont ouvertes ou non
			if($inscriptions_ouvertes) {
				$html .= <<<EOT
	<button onclick="spoiler('{$epreuve->id}')">S''Inscrire</button>

	<div id="spoiler-{$epreuve->id}">
		<!-- Div masquée par défaut -->
		<div>
			<form method="post" action="details.html">
				Numéro de participant :
				<input type="number" name="num" required="required">
				<input type="submit">
			</form>
		</div>

		<div>
			<form method="post" action="details.html">
				OU
				<p><input type="text" name="nom" placeholder="Nom" required="required"></p>
				<p><input type="text" name="prenom" placeholder="Prénom" required="required"></p>
				<p><input type="text" name="adresse" placeholder="Adresse" required="required"></p>
				<p><input type="text" name="ville" placeholder="Ville" required="required"></p>
				<p><input type="text" name="cp" placeholder="Code Postal" required="required"></p>
				<p><input type="tel" name="tel" placeholder="Téléphone" required="required"></p>
				<p><input type="submit" value="Inscription"></p>
			</form>
		</div>
	</div>
EOT;
			}
			else {
				// Inscriptions fermées
				$classement = \sportnet\model\classer::findById($epreuve->id);
				if($classement !== null && $classement !== false) {
					$html .= <<<EOT
	<button onclick="spoiler('{$epreuve->id}')">Classement</button>

	<div id="spoiler-{$epreuve->id}">
		<!-- Div masquée par défaut -->
		<ol>
EOT;

					foreach($classement as $participant)
						$html .= "\t\t\t<li>".$participant->participant." - ".$participant->temps."</li>\n";

					$html .= "\t\t</ol>\n";
					$html .= "\t</div>\n";
				}
			}

			$html .= "</div>\n";
		}

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
			case 'creerEvenement':
                $breadcrumb = $this->renderBreadcrumb(array('Créer un événement'));
				$main = $this->creer();
				break;

            case 'detailEvenement':
                $breadcrumb = $this->renderBreadcrumb();
    			$main = $this->detail();
    			break;

            case 'authentification':
                $breadcrumb = $this->renderBreadcrumb();
                $main = $this->authentification();
                break;

            case 'espaceOrganisateur':
                $breadcrumb = $this->renderBreadcrumb();
                //$main = $this->method();
        		break;

			case 'listEvents':
			default:
                // Liste des events
                $breadcrumb = $this->renderBreadcrumb();
                $main = $this->listEvents();
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
		<link rel="shortcut icon" href="/favicon.ico">
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
