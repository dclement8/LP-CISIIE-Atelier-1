<?php
namespace sportnet\control;
class SportnetController {

    /* Attribut pour stocker l'objet HttpRequest */ 
    private $request=null; 
    
    public function __construct(\sportnet\utils\HttpRequest $http_req){
        $this->request = $http_req ;
    }
	

	// Lister les événements
    public function listEvents()
	{
		$listeEvents = \sportnet\model\evenement::findAll();
		$view = new \sportnet\view\SportnetView($listeEvents);
		$view->render('listEvents');
    }
	
	// Détailler l'événement
	public function detailEvenement()
	{
		if(isset($_GET["event"]))
		{
			$event = \sportnet\model\evenement::findById($_GET["event"]);
			if($event == null)
			{
				$ctrl = new \sportnet\control\SportnetController($this->request);
				$ctrl->listEvents();
			}
			else
			{
				// $data[0] contient les données de l'événement
				// $data[1] contient les données des épreuves de l'événement
				// $data[2] contient les données du classement des épreuves de l'évenement
				
				$data[] = $event;
				$listeEpreuves = $event->getEpreuves();
				$data[] = $listeEpreuves;
				
				// Etats d'un événement :
				//
				//	1 ==> événement créé mais invisible pour les participants
				//	2 ==> événement créé et visible
				//	3 ==> événement ouvert aux inscriptions (si datelimite n'est pas dépassée)
				//	4 ==> événement où le classement est disponible
				
				if($event->etat == 1)
				{
					// événement créé mais invisible pour les participants
					$auth = new \wikiapp\utils\Authentification();
	
					if($auth->logged_in == true)
					{
						$view = new \sportnet\view\SportnetView($data);
						$view->render('detailEvenement');
					}
					else
					{
						$view = new \sportnet\view\SportnetView($data);
						$view->render('listEvents');
					}
				}
				else
				{
					if($event->etat == 4)
					{
						$epreuves = $event->getEpreuves();
						foreach($epreuves as $uneEpreuve)
						{
							$data[][] = \sportnet\model\classer::findById($uneEpreuve->id);
						}
					}
					
					$view = new \sportnet\view\SportnetView($data);
					$view->render('detailEvenement');
				}
			}
		}
		else
		{
			$ctrl = new \sportnet\control\SportnetController($this->request);
			$ctrl->listEvents();
		}
    }
	
	// Créer/modifier un événement
	public function creerEvenement()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			if(isset($_POST["nom"]) && isset($_POST["etat"]) && isset($_POST["date"]) && isset($_POST["description"]) && isset($_POST["tarif"]) && isset($_POST["nom_epreuve"]) && isset($_POST["date_epreuve"]) && isset($_POST["dist_epreuve"]))
			{
				$evenement = null;
				if(isset($_GET["event"]))
				{
					$evenement = \sportnet\model\evenement::findById($_GET["event"]);
				}
				else
				{
					$evenement = new \sportnet\model\evenement();
				}
				
				if($evenement == null)
				{
					$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
					$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
					$view->render('espaceOrganisateur');
				}
				else
				{
					$nom = filter_var($_POST["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$etat = filter_var($_POST["etat"], FILTER_SANITIZE_NUMBER_INT);
					$date = filter_var($_POST["date"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$description = filter_var($_POST["description"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$tarif = filter_var($_POST["tarif"], FILTER_SANITIZE_NUMBER_FLOAT);
					$discipline = filter_var($_POST["discipline"], FILTER_SANITIZE_NUMBER_INT);
					
					$nom_epreuve = filter_var($_POST["nom_epreuve"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$date_epreuve = filter_var($_POST["date_epreuve"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$dist_epreuve = filter_var($_POST["dist_epreuve"], FILTER_SANITIZE_NUMBER_INT);
					
					
					$evenement->nom = $nom;
					$evenement->description = $description;
					$evenement->etat = $etat;
					$evenement->dateheureLimiteInscription = $date;
					$evenement->tarif = $tarif;
					$evenement->discipline = \sportnet\model\discipline::findById($discipline);
					
					$retour = $evenement->save();
					
					$_SESSION["message"] = array();
					
					if($retour == true)
					{
						$epreuve = new \sportnet\model\epreuve();
						$epreuve->nom = $nom_epreuve;
						$epreuve->distance = $dist_epreuve;
						$epreuve->dateheure = $date_epreuve;
						$epreuve->evenement = $evenement;
						
						$retour = $epreuve->save();
						
						if($retour == true)
						{
							$_SESSION["message"][] = 1;
							$_SESSION["message"][] = "Evénement enregistré.";
						}
						else
						{
							$_SESSION["message"][] = 4;
							$_SESSION["message"][] = "Erreur lors de l'enregistrement de l'épreuve de l'événement.";
						}
					}
					else
					{
						// Message d'alerte :
						//
						// [0] : Correspond au code de retour (int)
						//		0 = Message d'alerte normal (class='alert')
						//		1 = Message d'alerte de succès (class='alert alert-success')
						//		2 = Message d'alerte d'information (class='alert alert-info')
						//		3 = Message d'alerte d'avertissement (class='alert alert-avert')
						//		4 = Message d'alerte de danger (class='alert alert-danger')
						//
						// [1] : Correspond au message d'alerte (string)
						
						$_SESSION["message"][] = 4;
						$_SESSION["message"][] = "Erreur lors de l'enregistrement de l'événement.";
					}
					
					$view = new \sportnet\view\SportnetView(null);
					$view->render('creerEvenement');
				}
			}
			else
			{
				$evenement = null;
				if(isset($_GET["event"]))
				{
					$evenement = \sportnet\model\evenement::findById($_GET["event"]);
				}
				
				$view = new \sportnet\view\SportnetView($evenement);
				$view->render('creerEvenement');
			}
		}
		else
		{
			$view = new \sportnet\view\SportnetView(null);
			$view->render('authentification');
		}
	}
	
	// Espace organisateur
	public function espaceOrganisateur()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
			$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
			$view->render('espaceOrganisateur');
		}
		else
		{
			$view = new \sportnet\view\SportnetView(null);
			$view->render('authentification');
		}
	}
	
	// Créer/modifier une épreuve
	public function creerEpreuve()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			if(isset($_POST["nom_epreuve"]) && isset($_POST["date_epreuve"]) && isset($_POST["dist_epreuve"]) && isset($_GET["event"]))
			{
				$epreuve = null;
				if(isset($_GET["epreuve"]))
				{
					$epreuve = \sportnet\model\epreuve::findById($_GET["epreuve"]);
				}
				else
				{
					$epreuve = new \sportnet\model\epreuve();
				}
				
				if($epreuve == null)
				{
					$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
					$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
					$view->render('espaceOrganisateur');
				}
				else
				{
					$nom_epreuve = filter_var($_POST["nom_epreuve"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$date_epreuve = filter_var($_POST["date_epreuve"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$dist_epreuve = filter_var($_POST["dist_epreuve"], FILTER_SANITIZE_NUMBER_INT);
					
					$evenement = \sportnet\model\evenement::findById($_GET["event"]);
					
					if($evenement == null)
					{
						$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
						$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
						$view->render('espaceOrganisateur');
					}
					else
					{
						$epreuve->nom = $nom_epreuve;
						$epreuve->distance = $dist_epreuve;
						$epreuve->dateheure = $date_epreuve;
						$epreuve->evenement = $evenement;
						
						$retour = $epreuve->save();
						$_SESSION["message"] = array();
						if($retour == true)
						{
							$_SESSION["message"][] = 1;
							$_SESSION["message"][] = "Epreuve enregistrée.";
						}
						else
						{
							$_SESSION["message"][] = 4;
							$_SESSION["message"][] = "Erreur lors de l'enregistrement de l'épreuve.";
						}
						
						$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
						$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
						$view->render('espaceOrganisateur');
					}
				}
			}
			else
			{
				$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
				$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
				$view->render('espaceOrganisateur');
			}
		}
		else
		{
			$view = new \sportnet\view\SportnetView(null);
			$view->render('authentification');
		}
	}
	
	// Supprimer événement
	public function supprimerEvenement()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			$evenement = null;
			if(isset($_GET["event"]))
			{
				$evenement = \sportnet\model\evenement::findById($_GET["event"]);
				
				if($evenement == null)
				{
					$view = new \sportnet\view\SportnetView(null);
					$view->render('espaceOrganisateur');
				}
				else
				{
					$retour = $evenement->delete();
					
					$_SESSION["message"] = array();
					if($retour == true)
					{
						$_SESSION["message"][] = 1;
						$_SESSION["message"][] = "Evenement supprimé.";
					}
					else
					{
						$_SESSION["message"][] = 4;
						$_SESSION["message"][] = "Erreur lors de la suppression de l'événement.";
					}
					
					$view = new \sportnet\view\SportnetView(null);
					$view->render('espaceOrganisateur');
				}
			}
			else
			{
				$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
				$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
				$view->render('espaceOrganisateur');
			}
		}
		else
		{
			$view = new \sportnet\view\SportnetView(null);
			$view->render('authentification');
		}
	}
	
	// Supprimer épreuve
	public function supprimerEpreuve()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			$epreuve = null;
			if(isset($_GET["epreuve"]))
			{
				$epreuve = \sportnet\model\evenement::findById($_GET["epreuve"]);
			}
			else
			{
				$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
				$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
				$view->render('espaceOrganisateur');
			}
			
			if($epreuve == null)
			{
				$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
				$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
				$view->render('espaceOrganisateur');
			}
			else
			{
				$retour = $epreuve->delete();
				
				$_SESSION["message"] = array();
				if($retour == true)
				{
					$_SESSION["message"][] = 1;
					$_SESSION["message"][] = "Epreuve supprimée.";
				}
				else
				{
					$_SESSION["message"][] = 4;
					$_SESSION["message"][] = "Erreur lors de la suppression de l'épreuve.";
				}
				
				$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
				$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
				$view->render('espaceOrganisateur');
			}
		}
		else
		{
			$view = new \sportnet\view\SportnetView(null);
			$view->render('authentification');
		}
	}
	
	// Inscription organisateur
	public function inscrireOrganisateur()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
			$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
			$view->render('espaceOrganisateur');
		}
		else
		{
			if(isset($_POST["login"]) && isset($_POST["mdp"]) && isset($_POST["mdp2"]) && isset($_GET["nom"]) && isset($_GET["prenom"]) && isset($_GET["adresse"]) && isset($_GET["ville"]) && isset($_GET["cp"]) && isset($_GET["tel"]))
			{
				$login = filter_var($_POST["login"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$mdp = filter_var($_POST["mdp"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$mdp2 = filter_var($_POST["mdp2"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$nom = filter_var($_POST["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$prenom = filter_var($_POST["prenom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$adresse = filter_var($_POST["adresse"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$ville = filter_var($_POST["ville"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$cp = filter_var($_POST["cp"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$tel = filter_var($_POST["tel"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				
				if($mdp == $mdp2)
				{
					$organisateur = new \sportnet\model\organisateur();
					
					$organisateur->login = $login;
					$organisateur->mdp = password_hash ($mdp, PASSWORD_DEFAULT);
					$organisateur->nom = $nom;
					$organisateur->prenom = $prenom;
					$organisateur->adresse = $adresse;
					$organisateur->cp = $cp;
					$organisateur->ville = $ville;
					$organisateur->tel = $tel;
					
					$retour = $organisateur->save();
				
					$_SESSION["message"] = array();
					if($retour == true)
					{
						$_SESSION["message"][] = 1;
						$_SESSION["message"][] = "Votre compte organisateur a été créé.";
					}
					else
					{
						$_SESSION["message"][] = 4;
						$_SESSION["message"][] = "Erreur lors de la création du compte organisateur.";
					}
					
					$view = new \sportnet\view\SportnetView(null);
					$view->render('authentification');
				}
				else
				{
					$_SESSION["message"] = array();
					$_SESSION["message"][] = 3;
					$_SESSION["message"][] = "Les mots de passe ne correspondent pas";
					
					$view = new \sportnet\view\SportnetView(null);
					$view->render('authentification');
				}
			}
		}
	}
	
	// Connexion/déconnexion de l'organisateur
	public function connexion()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			$auth->logout();
			$view = new \sportnet\view\SportnetView(null);
			$view->render('authentification');
		}
		else
		{
			if(isset($_POST["login"]) && isset($_POST["mdp"]))
			{
				$login = filter_var($_POST["login"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$mdp = filter_var($_POST["mdp"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$auth->login($login, $mdp);
				if($auth->logged_in == true)
				{
					$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
					$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
					$view->render('espaceOrganisateur');
				}
				else
				{
					$_SESSION["message"] = array();
					$_SESSION["message"][] = 3;
					$_SESSION["message"][] = "Login/mot de passe incorrects";
					
					$view = new \sportnet\view\SportnetView(null);
					$view->render('authentification');
				}
			}
		}
	}
	
	// Inscription d'un participant à une épreuve via un numéro de participant
	public function inscrireEpreuveViaNum()
	{
		if(isset($_POST["num"]) && isset($_GET["epreuve"]))
		{
			$num = filter_var($_POST["num"], FILTER_SANITIZE_NUMBER_INT);
			
			$participant = \sportnet\model\participant::findById($num);
			$epreuve = \sportnet\model\epreuve::findById($_GET["epreuve"]);
			
			if(($participant == null) || ($epreuve == null))
			{
				$ctrl = new \sportnet\control\SportnetController($this->request);
				$ctrl->listEvents();
			}
			else
			{
				$inscrit = new \sportnet\model\inscrit();
				
				$inscrit->dossard = \sportnet\model\inscrit::getMaxDossard($epreuve);
				$inscrit->epreuve = $epreuve;
				$inscrit->participant = $participant;
				
				$retour = $inscrit->save();
				
				$_SESSION["message"] = array();
				if($retour == true)
				{
					$_SESSION["message"][] = 1;
					$_SESSION["message"][] = "Vous êtes à présent inscrit à cette épreuve.";
				}
				else
				{
					$_SESSION["message"][] = 4;
					$_SESSION["message"][] = "Erreur lors de l'inscription à l'épreuve";
				}
				
				$ctrl = new \sportnet\control\SportnetController($this->request);
				$ctrl->listEvents();
			}
		}
		else
		{
			$ctrl = new \sportnet\control\SportnetController($this->request);
			$ctrl->listEvents();
		}
	}
	
	// Inscription d'un participant à une épreuve sans numéro
	public function inscrireEpreuve()
	{
		if(isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["adresse"]) && isset($_POST["ville"]) && isset($_POST["cp"]) && isset($_POST["tel"]) && isset($_GET["epreuve"]))
		{
			$nom = filter_var($_POST["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$prenom = filter_var($_POST["prenom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$adresse = filter_var($_POST["adresse"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$ville = filter_var($_POST["ville"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$cp = filter_var($_POST["cp"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$tel = filter_var($_POST["tel"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			
			$epreuve = \sportnet\model\epreuve::findById($_GET["epreuve"]);
			
			if($epreuve == null)
			{
				$ctrl = new \sportnet\control\SportnetController($this->request);
				$ctrl->listEvents();
			}
			else
			{
				$participant = new \sportnet\model\participant();
				$participant->nom = $nom;
				$participant->prenom = $prenom;
				$participant->rue = $adresse;
				$participant->cp = $cp;
				$participant->ville = $ville;
				$participant->tel = $tel;
				
				$retour = $participant->save();
				$_SESSION["message"] = array();
				if($retour == true)
				{
					$inscrit = new \sportnet\model\inscrit();
				
					$inscrit->dossard = \sportnet\model\inscrit::getMaxDossard($epreuve);
					$inscrit->epreuve = $epreuve;
					$inscrit->participant = $participant;
					
					$retour = $inscrit->save();
					
					$_SESSION["message"] = array();
					if($retour == true)
					{
						$_SESSION["message"][] = 1;
						$_SESSION["message"][] = "Vous êtes à présent inscrit à cette épreuve. Notez votre numéro de participant : ".\sportnet\model\participant::findByName($nom)->id;
					}
					else
					{
						$_SESSION["message"][] = 4;
						$_SESSION["message"][] = "Erreur lors de l'inscription à l'épreuve";
					}
					
					$ctrl = new \sportnet\control\SportnetController($this->request);
					$ctrl->listEvents();
				}
				else
				{
					$_SESSION["message"][] = 4;
					$_SESSION["message"][] = "Erreur lors de l'ajout du participant dans la base de données";
				}
			}
		}
		else
		{
			$ctrl = new \sportnet\control\SportnetController($this->request);
			$ctrl->listEvents();
		}
	}
	
	// Evénements d'un organisateur (Mes événements)
	public function evenementsOrganisateur()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
			
			$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
			$view->render('espaceOrganisateur');
		}
		else
		{
			$view = new \sportnet\view\SportnetView(null);
			$view->render('authentification');
		}
	}
	
	// Télécharger la liste des participants à une épreuve
	public function telechargerListe()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			$epreuve = \sportnet\model\epreuve::findById($_GET["epreuve"]);
			
			if($epreuve == null)
			{
				$ctrl = new \sportnet\control\SportnetController($this->request);
				$ctrl->listEvents();
			}
			else
			{
				$tabInscrits = array();
				
				$lesInscrits = \sportnet\model\inscrit::findById($_GET["epreuve"]);
				
				if($lesInscrits == null)
				{
					$ctrl = new \sportnet\control\SportnetController($this->request);
					$ctrl->listEvents();
				}
				else
				{
					$nomFichier = 'upload/listeParticipants_epreuve'.$_GET["epreuve"].'_'.time().'.csv';
					$csv = new SplFileObject($nomFichier, 'w');
					
					// Entête CSV
					$infoInscrit = array();
					$infoInscrit[] = "Numéro dossard";
					$infoInscrit[] = "Numéro participant";
					$infoInscrit[] = "Nom";
					$infoInscrit[] = "Prénom";
					$infoInscrit[] = "Rue";
					$infoInscrit[] = "Code Postal";
					$infoInscrit[] = "Ville";
					$infoInscrit[] = "Téléphone";
					$tabInscrits[] = $infoInscrit;
					
					foreach($lesInscrits as $unInscrit)
					{
						$infoInscrit = array();
						$infoInscrit[] = $unInscrit->dossard;
						$infoInscrit[] = $unInscrit->participant->id;
						$infoInscrit[] = $unInscrit->participant->nom;
						$infoInscrit[] = $unInscrit->participant->prenom;
						$infoInscrit[] = $unInscrit->participant->rue;
						$infoInscrit[] = $unInscrit->participant->cp;
						$infoInscrit[] = $unInscrit->participant->ville;
						$infoInscrit[] = $unInscrit->participant->tel;
						$tabInscrits[] = $infoInscrit;
					}
					
					foreach ($tabInscrits as $unInscrit)
					{
						$line = '"';
						$line .= implode('";"', $unInscrit);
						$line .= '"';
						$line .= "\r\n";
					 
						 $csv->fwrite($line);
					}
					
					header("Location: ".$nomFichier);
					exit;
				}
			}
		}
		else
		{
			$ctrl = new \sportnet\control\SportnetController($this->request);
			$ctrl->listEvents();
		}
	}
	
	// Upload du fichier CSV du classement + enregistrement dans la base de données
	public function uploadClassement()
	{
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			
		}
		else
		{
			$ctrl = new \sportnet\control\SportnetController($this->request);
			$ctrl->listEvents();
		}
	}
}