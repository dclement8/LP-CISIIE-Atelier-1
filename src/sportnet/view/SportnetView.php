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

	// Prend en paramètre un temps t (en centième de s)
	// Retourne un string sous la forme nb heures:nb minutes:nb secondes:nb centièmes"
    protected function afficherTemps($t)
	{
		// 1s  = 100 c
		// 1mn = 6 000 c
		// 1h  = 360 000 c
		$r = array();

		$r['h'] = floor($t/360000);	// Nombre d'heures
		$t -= $r['h'] * 360000;

		$r['m'] = floor($t/6000);	// Nombre de minutes
		$t -= $r['m'] * 6000;

		$r['s'] = floor($t/100);	// Nombre de secondes
		$t -= $r['s'] * 100;

		$r['c'] = $t;

		// Ajout de '0' devant nombres à 1 chiffre
		foreach($r as $key => $i) {
			if($i < 10)
				$r[$key] = '0'.$i;
		}

		return $r['h'].':'.$r['m'].':'.$r['s'].':'.$r['c'].'"';
	}

    protected function creer() {
		// $data contient les disciplines
		// ou avoir avec findAll ?
        $html = <<<EOT
<div class="event offset-0 span-4">
	<form method="post" action="{$this->script_name}/creerEvenement/">
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
                <label>Visible<br />(Inscription fermées)</label>
                <input type="radio" name="etat" value="2">
            </p>
            <p>
                <label>Visible<br />(Inscription ouvertes)</label>
                <input type="radio" name="etat" value="3">
            </p>
        </div>
        <p>
            <label>Date limite d&#39;inscription<br />(dd-mm-aaaa hh:mm)</label>
            <input type="text" name="date" required="required">
        </p>
        <p>
			<br />
            <label>Tarif :</label>
            <input type="number" name="tarif" min="0" required="required"> €
        </p>
        <p>
            <label>Description :</label><br />
            <textarea name="description" cols="50" rows="7" required="required"></textarea>
        </p>

        <h3>Ajouter une épreuve</h3>
        <p><input type="text" name="nom_epreuve" placeholder="Nom" required="required"></p>
        <p>
            Date :<br />
            <input type="text" name="date_epreuve" required="required">
        </p>
        <p><input type="number" name="dist_epreuve" placeholder="Distance (en m)" min="1" max="100000" required="required"></p>
        <p><input type="submit" class="btn" value="Créer"></p>
	</form>
</div>
EOT;
        return $html;
    }

	protected function authentification() {
		$html = <<<EOT
<div class="bloc offset-0 span-6">
	<form method="post" action="{$this->script_name}/connexion/">
		<h3>Connexion</h3>
			<p><input type="text" name="login" placeholder="Login" required="required"></p>
			<p><input type="password" name="mdp" placeholder="Mot de passe" required="required"></p>
			<p><input type="submit" class="btn" value="Connexion"></p>
	</form>
</div>

<div class="bloc offset-0 span-6">
	<form method="post" action="{$this->script_name}/inscrireOrganisateur/">
		<h3>Inscription</h3>

		<p><input type="text" name="login" placeholder="Login" required="required"></p>
		<p><input type="password" name="mdp" placeholder="Mot de passe" required="required"></p>
		<p><input type="password" name="mdp2" placeholder="Confirmation" required="required"></p>
		<p><input type="text" name="nom" placeholder="Nom" required="required"></p>
		<p><input type="text" name="prenom" placeholder="Prénom" required="required"></p>
		<p><input type="text" name="adresse" placeholder="Adresse" required="required"></p>
		<p><input type="text" name="ville" placeholder="Ville" required="required"></p>
		<p><input type="text" name="cp" maxlength="5" placeholder="Code Postal" required="required"></p>
		<p><input type="tel" name="tel" maxlength="10" placeholder="Téléphone" required="required"></p>
		<p><input type="submit" class="btn" value="Inscription"></p>
	</form>
</div>
EOT;
		return $html;
	}

	protected function listEvents() {
		// $data contient tous les événements
		$html = '';

		foreach($this->data as $event) {
			//var_dump($event);
			if($event->etat != 1)
			{
				$description = $event->description;
				if(strlen($description) > 1000)
					$description = substr($description, 0, 1000).' [...]';
					$date = date_format($event->dateheureLimiteInscription,"d-m-Y H:i");

				$html .= <<<EOT
<div class="event offset-0 span-3">
	<h3>{$event->nom}</h3>

	<p>${description}</p>
	<p>Le {$date}</p>
	<h4 class="bottom_plus"><a href="{$this->script_name}/evenement/?event={$event->id}">≡ En savoir plus</a></h4>
</div>
EOT;
			}
		}

		return $html;
	}

	protected function detail() {
		// $data contient un événement et son/ses épreuve(s)

		$laDate = date_format($this->data->dateheureLimiteInscription,"d-m-Y H:i");

		$html = <<<EOT
<div class="event large">
	<h3 class="centre">{$this->data->nom}</h3>
	Début le {$laDate}
	<p>Partager : (cliquez sur le lien pour le copier dans votre presse-papier)<input type="text" id="partager" size="64"></p>
	<hr>
	<p class="description">{$this->data->description}</p>
EOT;

		if($this->data->etat == 3 && time() <= $this->data->dateheureLimiteInscription->getTimestamp()) {
			$inscriptions_ouvertes = true;
			$html .= "\t<div class='alert alert-info'>Les inscriptions sont ouvertes</div>\n";
		}
		else {
			$inscriptions_ouvertes = false;
			$html .= "\t<div class='alert alert-avert'>Les inscriptions sont fermées</div>\n";
		}

		// Récupérer épreuves
		$html .= "<div class='line'>\n";
		$lesEpreuves = $this->data->getEpreuves();
		foreach($lesEpreuves as $epreuve) {
			$laDate = date_format($epreuve->dateheure,"d-m-Y H:i");
			$html .= <<<EOT
	<div class="epreuve offset-0 span-3">
		<h4>{$epreuve->nom}</h4>
		<ul>
			<li>{$laDate}</li>
			<li>{$epreuve->distance}m</li>
		</ul>
EOT;
			// L'affichage est différent si les Inscriptions sont ouvertes ou non
			if($inscriptions_ouvertes) {
				$html .= <<<EOT
		<button id="btn-spoiler-{$epreuve->id}" class="btn btn-red">↓ S&#39;Inscrire</button>

		<div id="spoiler-{$epreuve->id}">
			<!-- Div masquée par défaut -->
			<div>
				<form method="post" action="{$this->script_name}/inscrireEpreuveViaNum/?epreuve={$epreuve->id}">
					Numéro de participant :
					<input type="number" name="num" required="required">
					<input type="submit" class="btn" value="Valider l'inscription">
				</form>
			</div>

			<div>
				<form method="post" action="{$this->script_name}/inscrireEpreuve/?epreuve={$epreuve->id}">
					OU
					<p><input type="text" name="nom" placeholder="Nom" required="required"></p>
					<p><input type="text" name="prenom" placeholder="Prénom" required="required"></p>
					<p><input type="text" name="adresse" placeholder="Adresse" required="required"></p>
					<p><input type="text" name="ville" placeholder="Ville" required="required"></p>
					<p><input type="text" name="cp" placeholder="Code Postal" required="required"></p>
					<p><input type="tel" name="tel" placeholder="Téléphone" required="required"></p>
					<p><input type="submit" class="btn" value="Inscription"></p>
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
		<button id="btn-spoiler-{$epreuve->id}" class="btn btn-red">↓ Classement</button>

		<div id="spoiler-{$epreuve->id}">
			<!-- Div masquée par défaut -->
			<table>
EOT;
					$i = 1;
					foreach($classement as $participant) {
						$html .= "\t\t\t<tr><td>".$i."</td><td>".$participant->participant->nom." ".$participant->participant->prenom."</td><td>".$this->afficherTemps($participant->temps)."</td></tr>\n";
						$i++;
					}

					$html .= "\t\t</table>\n";
					$html .= "\t</div>\n";
				}
			}

			$html .= "</div>\n";
		}

		$html .= "</div>\n";
		return $html;
	}

	protected function mesEvents() {
		// $data contient un ou des événement(s) avec son/ses épreuve(s)
		$html = '';
		//var_dump($this->data);
		if($this->data != null)
		{
		foreach($this->data as $event)
		{
			$html .= <<<EOT
<div class="event offset-0 span-4">
	<h3>{$event->nom}</h3>

	<form method="post" action="{$this->script_name}/creerEvenement/?event={$event->id}">
		<p>
			<label>Nom :</label>
			<input type="text" name="nom" placeholder="Nom" value="{$event->nom}" required="required">
		</p>
		<p>
			<label>Discipline :</label>
			<select name="discipline">
EOT;
			$event_discipline = $event->discipline->id; // Id de la discipline de l'événement actuel
			$disciplines = \sportnet\model\discipline::findAll();
			foreach($disciplines as $discipline) {
				$html .= "\t\t\t\t<option value='".$discipline->id."'";
				if($discipline->id == $event_discipline)
					$html .= " selected='selected'";
				$html .= ">".$discipline->nom."</option>\n";
			}

			$html .= <<<EOT
			</select>
		</p>
		<fieldset>
			<legend>Etat :</legend>
				<label>Invisible :</label>
				<input type="radio" name="etat" value="1"
EOT;
			if($event->etat == 1)
				$html .= ' checked';
			$html .= <<<EOT
				>
				<br />
				<label>Visible<br />(Inscription fermées)</label>
				<input type="radio" name="etat" value="2"
EOT;
			if($event->etat == 2)
				$html .= ' checked';
			$html .= <<<EOT
				>
				<br />
				<label>Visible<br />(Inscription ouvertes)</label>
				<input type="radio" name="etat" value="3"
EOT;
			if($event->etat == 3)
				$html .= ' checked';

			$laDate = date_format($event->dateheureLimiteInscription,"d-m-Y H:i");

			$html .= <<<EOT
				>
		</fieldset>
		<p>
			<label>Date limite d&#39;inscription<br />(dd-mm-aaaa hh:mm)</label>
			<input type="text" name="date" value="{$laDate}" required="required">
		</p>
		<p>
			<br />
			<label>Tarif :</label>
			<input type="number" name="tarif" min="0" value="{$event->tarif}" required="required"> €
		</p>
		<p>
			<label>Description :</label><br />
			<textarea name="description" cols="50" rows="7" required="required">{$event->description}</textarea>
		</p>

		<p>
			<input type="submit" class="btn" value="Modifier">
			<button class="btn delete" id="{$this->script_name}/supprimerEvenement/?event={$event->id}">Supprimer</button>
		</p>
	</form>
EOT;


			if($event->etat == 3 && time() <= $event->dateheureLimiteInscription->getTimestamp()) {
				$inscriptions_ouvertes = true;
			}
			else
				$inscriptions_ouvertes = false;

			if($inscriptions_ouvertes) {
				$html .= <<<EOT
	<button id="btn-spoiler-{$event->id}_1" class="btn btn-red">≡ Ajouter une épreuve</button>

	<div id="spoiler-{$event->id}_1">
		<!-- Div masquée par défaut -->
		<!-- Ajout d&#39;une épreuve -->
		<form method="post" action="{$this->script_name}/creerEpreuve/?event={$event->id}">
			<p><input type="text" name="nom_epreuve" placeholder="Nom" required="required"></p>
			<p>
				Date :<br />
				<input type="text" name="date_epreuve" required="required">
			</p>
			<p><input type="number" name="dist_epreuve" placeholder="Distance (en m)" min="1" max="100000" required="required"></p>
			<p><input type="submit" class="btn" value="Ajouter"></p>
		</form>
	</div>
EOT;
			}

			$html .= <<<EOT
	<button id="btn-spoiler-{$event->id}_2" class="btn btn-red">≡ Voir les épreuves</button>

	<div id="spoiler-{$event->id}_2">
		<!-- Div masquée par défaut -->
		<!-- Voir/modifier une épreuve -->
EOT;
			foreach($event->getEpreuves() as $epreuve) {
				$laDate = date_format($epreuve->dateheure,"d-m-Y H:i");
				$html .= <<<EOT
		<h4>{$epreuve->nom}</h4>
		<form method="post" action="{$this->script_name}/creerEpreuve/?event={$event->id}&epreuve={$epreuve->id}">
			<p><input type="text" name="nom_epreuve" placeholder="Nom" value="{$epreuve->nom}" required="required"></p>
			<p>
				Date :<br />
				<input type="text" name="date_epreuve" value="{$laDate}" required="required">
			</p>
			<p><input type="number" name="dist_epreuve" placeholder="Distance (en m)" value="{$epreuve->distance}" min="1" max="100000" required="required"></p>
			<p>
				<input type="submit" class="btn" value="Modifier">
				<button class="btn delete" id="{$this->script_name}/supprimerEpreuve/?epreuve={$epreuve->id}">Supprimer</button>
			</p>
		</form>
		<hr>
EOT;
				if(!$inscriptions_ouvertes) {
					$html .= <<<EOT
		<form method="post" enctype="multipart/form-data" action="{$this->script_name}/uploadClassement/?epreuve={$epreuve->id}">
			<p><a href="{$this->script_name}/telechargerListe/?epreuve={$epreuve->id}" id="liste-{$epreuve->id}">Télécharger liste d&#39;engagement</a></p>
			<p>
				Upload classement : <input type="file" name="csv"> <input type="submit" class="btn" name="uploader" value="Uploader le classement">
			</p>
		</form>
EOT;
				}

				$html .= "\t\t<hr><strong>Participants :</strong>\n";
				$html .= "\t\t<table>\n";
				$html .= "\t\t\t<tr><th>Nom</th><th>Dossard</th></tr>\n";

				$participants = \sportnet\model\inscrit::findById($epreuve->id);
				if($participants !== null && $participants !== false) {
					foreach($participants as $participant) {
						$html .= "\t\t\t<tr><td>".$participant->participant->nom." ".$participant->participant->prenom."</td><td>".$participant->dossard."</td></tr>\n";
					}
				}

				$html .= "\t\t</table>\n";
			}

			$html .= "\t</div></div>\n";
		}
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
                $breadcrumb = $this->renderBreadcrumb(array(array('Créer un événement', '/creerEvenement/')));
				$main = $this->creer();
				break;

            case 'detailEvenement':
                $breadcrumb = $this->renderBreadcrumb(array(array('Détail d\'événement', '/evenement/')));
    			$main = $this->detail();
    			break;

            case 'authentification':
                $breadcrumb = $this->renderBreadcrumb(array(array('Authentification', '/connexion/')));
                $main = $this->authentification();
                break;

            case 'espaceOrganisateur':
                $breadcrumb = $this->renderBreadcrumb(array(array('Espace organisateur', '/espace/')));
                $main = $this->mesEvents();
        		break;

			case 'listEvents':
				$breadcrumb = $this->renderBreadcrumb();
                $main = $this->listEvents();
				break;

			default:
                // Liste des events
                $breadcrumb = $this->renderBreadcrumb();
                $main = $this->listEvents();
				break;
        }

        $style_file = $this->app_root.'css/main.css';

        $header 	= $this->renderHeader();
        $menu   	= $this->renderMenu();
		$messages	= $this->renderMessage();

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
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<link rel="shortcut icon" href="{$this->app_root}/favicon.ico">
        <link rel="stylesheet" href="${style_file}">
		<script type="text/javascript" src="{$this->app_root}/js/sportnet.js"></script>
    </head>

    <body>
        ${header}
        ${menu}
		${breadcrumb}
		${messages}

		<div class="container line">
			${main}
		</div>
    </body>
</html>
EOT;

    echo $html;

    }


}
