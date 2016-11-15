<?php
namespace sportnet\model;
class epreuve extends AbstractModel {
	private $id;
	private $nom;
	private $distance;
	private $dateheure;
	private $evenement;
	
	
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
	
	public function getEvenement()
	{
		
	}
}
?>