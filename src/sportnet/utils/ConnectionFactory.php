<?php
	namespace sportnet\utils;
	
	class ConnectionFactory
	{
		private static $config;
		private static $db;
		
		public static function setConfig($nom_fichier)
		{
			self::$config = parse_ini_file($nom_fichier);
		}
		
		public static function makeConnection()
		{
			if(!(isset($db)))
			{
				$dsn = "mysql:host=".self::$config["host"].";dbname=".self::$config["base"];
				self::$db = new \PDO($dsn, self::$config["user"], self::$config["pass"]);
			}
			return self::$db;
		}
		
		public function __set ($attribut, $valeur)
		{
			if($attribut != null)
			{
				$this->$attribut = $valeur;
			}
			else
			{
				throw new \Exception("L'attribut n'existe pas !");
			}
		}
		public function __get ($attribut)
		{
			if($attribut != null)
			{
				return $this->$attribut;
			}
			else
			{
				throw new \Exception("L'attribut n'est pas défini !");
			}
		}
	}