<?php
namespace sportnet\model;
class evenement extends AbstractModel {
	private $id;
	private $nom;
	private $description;
	private $etat;
	private $dateheureLimiteInscription;
	private $tarif;
	private $discipline;
	private $organisateur;
	

	public function __construct()
	{
		$connect = new \sportnet\utils\ConnectionFactory();
		$connect->setConfig("conf/config.ini");
		self::$db = $connect->makeConnection();
		self::$db->query("SET CHARACTER SET utf8");
	}
	
	protected function update()
	{
		
	}
	
	protected function insert()
	{
		
	}
	
	public function save()
	{
		
	}
	
	public function delete()
	{
		
	}
	
	public static function findById($leId)
	{
		
	}
	
	public static function findAll()
	{
		
	}
	
	public static function findByName($leNom)
	{
		
	}
	
	public function getEpreuves()
	{
		
	}
	
	public function getOrganisateur()
	{
		
	}
	
	public function getDiscipline()
	{
		
	}
}
?>