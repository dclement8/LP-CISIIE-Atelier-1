<?php
namespace sportnet\utils;
class Authentification extends AbstractAuthentification
{
	public function __construct()
	{
		if(isset($_SESSION["user_login"]))
		{
			$this->user_login = $_SESSION["user_login"];
			$this->logged_in = true;
		}
		else
		{
			$this->user_login = null;
			$this->logged_in = false;
		}
	}
	
	// Vérification du couple Login/Mot de Passe renseigné par l'utilisateur avec celui de la base de données + Création variable SESSION si authentifié.
	public function login($leLogin, $lePassword)
	{
		$infos = \sportnet\model\organisateur::findByLogin($leLogin);
		if($infos == false)
		{
			// Login introuvable
			throw new \Exception("Login introuvable");
		}
		else
		{
			// Login trouvé
			if(password_verify($lePassword, $infos->pass))
			{
				// Mot de passe juste
				$this->user_login = $leLogin;
				$_SESSION["user_login"] = $this->user_login;
				$this->logged_in = true;
			}
		}
	}
	
	// Détruit la variable SESSION d'authentification si elle existe
	public function logout()
	{
		unset($_SESSION["user_login"]);
		$this->user_login = null;
		$this->logged_in = false;
	}
	
	public function createUser($leLogin, $lePassword, $leLevel)
	{

		$nouvelUser = new \sportnet\model\organisateur();
		$nouvelUser->login = $leLogin;
		$nouvelUser->mdp = password_hash($lePassword, PASSWORD_DEFAULT);
		$nouvelUser->save();
	}
}