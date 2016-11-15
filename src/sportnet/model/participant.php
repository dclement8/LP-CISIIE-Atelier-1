<?php
namespace sportnet\model;
class participant extends AbstractModel {
	private  $id;
	private  $nom;
	private  $prenom;
	private  $rue;
	private  $cp;
	private  $ville;
	private  $tel;
	
	
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
}
?>