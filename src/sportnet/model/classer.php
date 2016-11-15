<?php
namespace sportnet\model;
class classer extends AbstractModel {
	private  $position;
	private  $temps;
	
	// Objets associés
	private $epreuve;
	private $participant;
	
	
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
	
	public function getEpreuve()
	{
		
	}
	
	public function getParticipant()
	{
		
	}
}
?>