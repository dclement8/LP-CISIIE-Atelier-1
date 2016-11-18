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
		if(isset($this->request->get["event"]))
		{
			$event = \sportnet\model\evenement::findById($this->request->get["event"]);
			if($event == null)
			{
				$ctrl = new \sportnet\control\SportnetController($this->request);
				$ctrl->listEvents();
			}
			else
			{
				// Etats d'un événement :
				//
				//	1 ==> événement créé mais invisible pour les participants
				//	2 ==> événement créé et visible
				//	3 ==> événement ouvert aux inscriptions (si datelimite n'est pas dépassée)
				
				if($event->etat == 1)
				{
					// événement créé mais invisible pour les participants
					$auth = new \sportnet\utils\Authentification();
	
					if($auth->logged_in == true)
					{
						$view = new \sportnet\view\SportnetView($event);
						$view->render('detailEvenement');
					}
					else
					{
						$ctrl = new \sportnet\control\SportnetController($this->request);
						$ctrl->listEvents();
					}
				}
				else
				{
					$view = new \sportnet\view\SportnetView($event);
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
		// Message d'alerte : $_SESSION["message"]
		//
		// [0] : Correspond au code de retour (int)
		//		0 = Message d'alerte normal (class='alert')
		//		1 = Message d'alerte de succès (class='alert alert-success')
		//		2 = Message d'alerte d'information (class='alert alert-info')
		//		3 = Message d'alerte d'avertissement (class='alert alert-avert')
		//		4 = Message d'alerte de danger (class='alert alert-danger')
		//
		// [1] : Correspond au message d'alerte (string)
		$auth = new \sportnet\utils\Authentification();
		
		if($auth->logged_in == true)
		{
			if(isset($this->request->post["nom"]) && isset($this->request->post["etat"]) && isset($this->request->post["date"]) && isset($this->request->post["description"]) && isset($this->request->post["tarif"]))
			{
				$evenement = null;
				
				$nom = filter_var($this->request->post["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$etat = filter_var($this->request->post["etat"], FILTER_SANITIZE_NUMBER_INT);
				$date = filter_var($this->request->post["date"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$description = filter_var($this->request->post["description"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$tarif = filter_var($this->request->post["tarif"], FILTER_SANITIZE_NUMBER_FLOAT);
				$discipline = filter_var($this->request->post["discipline"], FILTER_SANITIZE_NUMBER_INT);
				
				if((\DateTime::createFromFormat("d-m-Y H:i", $date)) == false)
				{
					$_SESSION["message"][] = 3;
					$_SESSION["message"][] = "Date de l'événement invalide";
					$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
					$view->render('espaceOrganisateur');
				}
				else
				{
					if(!(isset($this->request->get["event"])))
					{
						if(isset($this->request->post["nom_epreuve"]) && isset($this->request->post["date_epreuve"]) && isset($this->request->post["dist_epreuve"]))
						{
							$evenement = new \sportnet\model\evenement();
							
							$nom_epreuve = filter_var($this->request->post["nom_epreuve"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
							$date_epreuve = filter_var($this->request->post["date_epreuve"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
							$dist_epreuve = filter_var($this->request->post["dist_epreuve"], FILTER_SANITIZE_NUMBER_INT);
							
							if((\DateTime::createFromFormat("d-m-Y H:i", $date_epreuve)) == false)
							{
								$_SESSION["message"][] = 3;
								$_SESSION["message"][] = "Date de l'événement invalide";
								$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
								$view->render('espaceOrganisateur');
							}
							else
							{
								$evenement->nom = $nom;
								$evenement->description = $description;
								$evenement->etat = $etat;
								$evenement->dateheureLimiteInscription = $date;
								$evenement->tarif = $tarif;
								$evenement->discipline = \sportnet\model\discipline::findById($discipline);
								$evenement->organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
								
								//var_dump($evenement->discipline);
								
								$retour = $evenement->save();
								
								$_SESSION["message"] = array();
								
								if($retour == true)
								{
									$epreuve = new \sportnet\model\epreuve();
									$epreuve->nom = $nom_epreuve;
									$epreuve->distance = $dist_epreuve;
									$epreuve->dateheure = $date_epreuve;
									
									$epreuve->evenement = \sportnet\model\evenement::getLastEvenement();
									
									
									$retour = $epreuve->save();
									
									if($retour == true)
									{
										$_SESSION["message"][] = 1;
										$_SESSION["message"][] = "Evénement enregistré.";
										
										$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
						
										$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
										$view->render('espaceOrganisateur');
									}
									else
									{
										$_SESSION["message"][] = 4;
										$_SESSION["message"][] = "Erreur lors de l'enregistrement de l'épreuve de l'événement.";
										
										$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
						
										$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
										$view->render('espaceOrganisateur');
									}
								}
								else
								{
									$_SESSION["message"][] = 4;
									$_SESSION["message"][] = "Erreur lors de l'enregistrement de l'événement";
									
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
						$evenement = \sportnet\model\evenement::findById($this->request->get["event"]);
						
						if($evenement == null)
						{
							$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
					
							$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
							$view->render('espaceOrganisateur');
						}
						else
						{
							$evenement->nom = $nom;
							$evenement->description = $description;
							$evenement->etat = $etat;
							$evenement->dateheureLimiteInscription = $date;
							$evenement->tarif = $tarif;
							$evenement->discipline = \sportnet\model\discipline::findById($discipline);
							$evenement->organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
							
							$retour = $evenement->save();
							
							$_SESSION["message"] = array();
							
							if($retour == true)
							{
								$_SESSION["message"][] = 1;
								$_SESSION["message"][] = "Evénement enregistré.";
							}
							else
							{
								$_SESSION["message"][] = 4;
								$_SESSION["message"][] = "Erreur lors de l'enregistrement de l'événement";
							}
							
							$organisateur = \sportnet\model\organisateur::findByLogin($auth->user_login);
					
							$view = new \sportnet\view\SportnetView($organisateur->getEvenements());
							$view->render('espaceOrganisateur');
						}
					}
				}
			}
			else
			{
				/*$evenement = null;*/
				/*if(isset($this->request->get["event"]))
				{
					$evenement = \sportnet\model\evenement::findById($this->request->get["event"]);
				}*/
				
				$discipline = \sportnet\model\discipline::findAll();
				
				$view = new \sportnet\view\SportnetView($discipline);
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
			if(isset($this->request->post["nom_epreuve"]) && isset($this->request->post["date_epreuve"]) && isset($this->request->post["dist_epreuve"]) && isset($this->request->get["event"]))
			{
				$epreuve = null;
				if(isset($this->request->get["epreuve"]))
				{
					$epreuve = \sportnet\model\epreuve::findById($this->request->get["epreuve"]);
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
					$nom_epreuve = filter_var($this->request->post["nom_epreuve"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$date_epreuve = filter_var($this->request->post["date_epreuve"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$dist_epreuve = filter_var($this->request->post["dist_epreuve"], FILTER_SANITIZE_NUMBER_INT);
					
					if((\DateTime::createFromFormat("d-m-Y H:i", $date_epreuve)) == false)
					{
						$_SESSION["message"][] = 3;
						$_SESSION["message"][] = "Date de l'événement invalide";
						$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
						$view->render('espaceOrganisateur');
					}
					else
					{
						$evenement = \sportnet\model\evenement::findById($this->request->get["event"]);
						
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
			if(isset($this->request->get["event"]))
			{
				$evenement = \sportnet\model\evenement::findById($this->request->get["event"]);
				
				if($evenement == null)
				{
					$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
					$view->render('espaceOrganisateur');
				}
				else
				{
					if($evenement->organisateur->login == $auth->user_login)
					{
						$epreuves = $evenement->getEpreuves();
						
						foreach ($epreuves as $uneEpreuve)
						{
							$classementEpreuve = \sportnet\model\classer::findById($uneEpreuve->id);
							if($classementEpreuve != null)
							{
								foreach ($classementEpreuve as $unClassement)
								{
									$unClassement->delete();
								}
							}
							
							$inscritsEpreuve = \sportnet\model\inscrit::findById($uneEpreuve->id);
							if($inscritsEpreuve != null)
							{
								foreach ($inscritsEpreuve as $unInscrit)
								{
									$unInscrit->delete();
								}
							}
							
							$uneEpreuve->delete();
						}
						
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
					}
					
					$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
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
			if(isset($this->request->get["epreuve"]))
			{
				$epreuve = \sportnet\model\evenement::findById($this->request->get["epreuve"]);
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
				if($epreuve->getEvenement()->organisateur->login == $auth->user_login)
				{
					$classementEpreuve = \sportnet\model\classer::findById($epreuve->id);
					if($classementEpreuve != null)
					{
						foreach ($classementEpreuve as $unClassement)
						{
							$unClassement->delete();
						}
					}
					
					$inscritsEpreuve = \sportnet\model\inscrit::findById($epreuve->id);
					if($inscritsEpreuve != null)
					{
						foreach ($inscritsEpreuve as $unInscrit)
						{
							$unInscrit->delete();
						}
					}
					
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
				}
				
				$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
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
			if(isset($this->request->post["login"]) && isset($this->request->post["mdp"]) && isset($this->request->post["mdp2"]) && isset($this->request->post["nom"]) && isset($this->request->post["prenom"]) && isset($this->request->post["adresse"]) && isset($this->request->post["ville"]) && isset($this->request->post["cp"]) && isset($this->request->post["tel"]))
			{
				$login = filter_var($this->request->post["login"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$mdp = filter_var($this->request->post["mdp"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$mdp2 = filter_var($this->request->post["mdp2"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$nom = filter_var($this->request->post["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$prenom = filter_var($this->request->post["prenom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$adresse = filter_var($this->request->post["adresse"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$ville = filter_var($this->request->post["ville"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$cp = filter_var($this->request->post["cp"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$tel = filter_var($this->request->post["tel"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				
				$trouverOrganisateur = \sportnet\model\organisateur::findByLogin($login);
				
				if($trouverOrganisateur == null)
				{
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
							$_SESSION["message"] = array();
							$_SESSION["message"][] = 1;
							$_SESSION["message"][] = "Votre compte organisateur a été créé.";
						}
						else
						{
							$_SESSION["message"] = array();
							$_SESSION["message"][] = 4;
							$_SESSION["message"][] = "Erreur lors de la création du compte organisateur.";
						}
					}
					else
					{
						$_SESSION["message"] = array();
						$_SESSION["message"][] = 3;
						$_SESSION["message"][] = "Les mots de passe ne correspondent pas";
					}
				}
				else
				{
					$_SESSION["message"] = array();
					$_SESSION["message"][] = 3;
					$_SESSION["message"][] = "Le login est déjà utilisé.";
					
					
				}
				
				$view = new \sportnet\view\SportnetView(null);
				$view->render('authentification');
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
			if(isset($this->request->post["login"]) && isset($this->request->post["mdp"]))
			{
				$login = filter_var($this->request->post["login"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$mdp = filter_var($this->request->post["mdp"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
			else
			{
				$view = new \sportnet\view\SportnetView(null);
				$view->render('authentification');
			}
		}
	}
	
	// Inscription d'un participant à une épreuve via un numéro de participant
	public function inscrireEpreuveViaNum()
	{
		if(isset($this->request->post["num"]) && isset($this->request->get["epreuve"]))
		{
			$num = filter_var($this->request->post["num"], FILTER_SANITIZE_NUMBER_INT);
			
			$participant = \sportnet\model\participant::findById($num);
			$epreuve = \sportnet\model\epreuve::findById($this->request->get["epreuve"]);
			
			if(($participant == null) || ($epreuve == null))
			{
				$ctrl = new \sportnet\control\SportnetController($this->request);
				$ctrl->listEvents();
			}
			else
			{
				// Vérifier si le participant est déjà inscrit
				$lesInscrits = \sportnet\model\inscrit::findById($this->request->get["epreuve"]);
				$estDejaInscrit = false;
				if($lesInscrits != null)
				{
					foreach($lesInscrits as $unInscrit)
					{
						if($unInscrit->participant->id == $this->request->post["num"])
						{
							$estDejaInscrit = true;
						}
					}
				}
				
				if($estDejaInscrit == true)
				{
					$_SESSION["message"][] = 3;
					$_SESSION["message"][] = "Le participant est déjà inscrit à l'épreuve !";
					
					$ctrl = new \sportnet\control\SportnetController($this->request);
					$ctrl->listEvents();
				}
				else
				{
					$inscrit = new \sportnet\model\inscrit();
					
					$inscrit->dossard = \sportnet\model\inscrit::getMaxDossard($epreuve->id) + 1;
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
		if(isset($this->request->post["nom"]) && isset($this->request->post["prenom"]) && isset($this->request->post["adresse"]) && isset($this->request->post["ville"]) && isset($this->request->post["cp"]) && isset($this->request->post["tel"]) && isset($this->request->get["epreuve"]))
		{
			$nom = filter_var($this->request->post["nom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$prenom = filter_var($this->request->post["prenom"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$adresse = filter_var($this->request->post["adresse"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$ville = filter_var($this->request->post["ville"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$cp = filter_var($this->request->post["cp"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$tel = filter_var($this->request->post["tel"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			
			$epreuve = \sportnet\model\epreuve::findById($this->request->get["epreuve"]);
			
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
				
					$inscrit->dossard = \sportnet\model\inscrit::getMaxDossard($epreuve->id) + 1;
					$inscrit->epreuve = $epreuve;
					$inscrit->participant = \sportnet\model\participant::getLastParticipant();
					
					$retour = $inscrit->save();
					
					$_SESSION["message"] = array();
					if($retour == true)
					{
						$_SESSION["message"][] = 1;
						$_SESSION["message"][] = "Vous êtes à présent inscrit à cette épreuve. Notez votre numéro de participant : ".\sportnet\model\participant::getLastParticipant()->id;
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
			$epreuve = \sportnet\model\epreuve::findById($this->request->get["epreuve"]);
			
			if($epreuve == null)
			{
				$ctrl = new \sportnet\control\SportnetController($this->request);
				$ctrl->listEvents();
			}
			else
			{
				$tabInscrits = array();
				
				$lesInscrits = \sportnet\model\inscrit::findById($this->request->get["epreuve"]);
				
				if($lesInscrits == null)
				{
					$ctrl = new \sportnet\control\SportnetController($this->request);
					$ctrl->listEvents();
				}
				else
				{
					$dir = "upload/";
					$nomFichier = 'listeParticipants_epreuve'.$this->request->get["epreuve"].'_'.time().'.csv';
					$csv = new \SplFileObject($dir.$nomFichier, 'w');
					
					// Entête CSV
					$infoInscrit = array();
					$infoInscrit[] = "Numero dossard";
					$infoInscrit[] = "Numero participant";
					$infoInscrit[] = "Nom";
					$infoInscrit[] = "Prenom";
					$infoInscrit[] = "Rue";
					$infoInscrit[] = "Code Postal";
					$infoInscrit[] = "Ville";
					$infoInscrit[] = "Telephone";
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
					
					// désactive la mise en cache
					header("Cache-Control: no-cache, must-revalidate");
					header("Cache-Control: post-check=0,pre-check=0");
					header("Cache-Control: max-age=0");
					header("Pragma: no-cache");
					header("Expires: 0");
					
					// force le téléchargement du fichier
					header("Content-Type: application/force-download");
					header('Content-Disposition: attachment; filename="'.$nomFichier.'"');
					
					// indique la taille du fichier à télécharger
					$size = filesize($dir.$nomFichier);
					header("Content-Length: ".$size);
					
					// envoi le contenu du fichier
					readfile($dir.$nomFichier);
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
			if((isset($_FILES["csv"])) && (isset($this->request->get['epreuve'])))
			{
				// Vérifier si l'épreuve existe
				$epreuve = \sportnet\model\epreuve::findById($this->request->get["epreuve"]);
			
				if($epreuve == null)
				{
					$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
					$view->render('espaceOrganisateur');
				}
				else
				{
					// On supprime d'abord le classement existant pour l'épreuve
					$classementEpreuve = \sportnet\model\classer::findById($this->request->get['epreuve']);
					if($classementEpreuve != null)
					{
						foreach ($classementEpreuve as $unClassement)
						{
							$unClassement->delete();
						}
					}
					
					$fichier = "NULL";
					$target_dir = "upload/";
					$target_file = $target_dir . basename($_FILES["csv"]["name"]);
					$nomfichier = basename($_FILES["csv"]["name"]);
					$uploadOk = true;
					$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
					$tailleMo = 32;

					if($_FILES["csv"]["tmp_name"] == "")
					{
						$_SESSION["message"][] = 3;
						$_SESSION["message"][] = "Aucun fichier reçu.";
						$uploadOk = false;
					}
					else
					{
						if($fileType != "csv")
						{
							$_SESSION["message"][] = 3;
							$_SESSION["message"][] = "Format de fichier incorrect. Le format CSV est seulement autorisés.";
							$uploadOk = false;
						}
						else
						{
							if (move_uploaded_file($_FILES["csv"]["tmp_name"], $target_file))
							{
								$uploadOk = true;
							}
							else
							{
								$_SESSION["message"][] = 3;
								$_SESSION["message"][] = "Erreur lors de l'upload du fichier.";
								$uploadOk = false;
								$fichier = "NULL";
							}
						}
					}	

					if($uploadOk == true)
					{
						if(isset($this->request->get['epreuve']))
						{
							if(\sportnet\model\epreuve::findById($this->request->get['epreuve']))
							{
								$tabObjClasser = array();
								$erreur = false;
								//chemin du fichier
								$fichier = "upload/$nomfichier";
								$tab = array();
								$csv = new \SplFileObject($fichier); // On instancie l'objet SplFileObject
								$csv->setFlags(\SplFileObject::READ_CSV); // On indique que le fichier est de type CSV
								$csv->setCsvControl(';'); // On indique le caractère délimiteur, ici c'est la virgule
								foreach($csv as $t) {
									$tab[] = $t;
								}
								
								$tableauInscrit = \sportnet\model\inscrit::findById($this->request->get['epreuve']);
								
								for($i = 0; $i < count($tab); $i++)
								{
									$trouverInscrit = false;
									for($j = 0; $j < count($tableauInscrit); $j++)
									{
										$unInscrit = $tableauInscrit[$j];
										
										// Le cas des cases vides
										if((isset($tab[$i][1])) && (isset($tab[$i][2])))
										{
											if($unInscrit->dossard == $tab[$i][1])
											{
												$trouverInscrit == true;
												$objclasser = new \sportnet\model\classer();
												$heure = $tab[$i][2];
												$number = explode(":", $heure);
												
												// Test si le temps est incorrect.
												if((isset($number[1])) && (isset($number[2])) && (isset($number[3])))
												{
													$res = ($number[0]*100*60*60) + ($number[1]*100*60) + ($number[2]*100) + $number[3];
												}
												else
												{
													// Cas incorrect alors on met 0
													$res = 0;
												}

												$objclasser->position = $tab[$i][0];
												$objclasser->temps = $res;
												$objclasser->participant = $unInscrit->participant;
												$objclasser->epreuve = $unInscrit->epreuve;
												
												
												
												$tabObjClasser[] = $objclasser;
											}
										}
									}
								}

								if($erreur == false)
								{
									foreach($tabObjClasser as $unObjClasser)
									{
										$unObjClasser->save();
									}
									$_SESSION["message"][] = 1;
									$_SESSION["message"][] = "Classement importé !";
								}
								else
								{
									$_SESSION["message"][] = 3;
									$_SESSION["message"][] = "Erreur de la structure du fichier csv";
								}

							} 
							else
							{
								$_SESSION["message"][] = 3;
								$_SESSION["message"][] = "Cette épreuve n'existe pas";
								
							}
							
						}
						
						// Suppression du fichier CSV des fichiers uploadés.
						chmod("upload/".$nomfichier, 0777);
						@unlink("upload/".$nomfichier);
						
						$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
						$view->render('espaceOrganisateur');
					}
					else
					{
						$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
						$view->render('espaceOrganisateur');
					}
				}
			}
			else
			{
				$view = new \sportnet\view\SportnetView(\sportnet\model\organisateur::findByLogin($auth->user_login)->getEvenements());
				$view->render('espaceOrganisateur');
			}
		}
		else
		{
			$ctrl = new \sportnet\control\SportnetController($this->request);
			$ctrl->listEvents();
		}
	}
}