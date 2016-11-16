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
		$update = "UPDATE epreuve SET nom = :nom, distance = :distance, dateheure = :dateheure, id_evenement = :id_evenement WHERE id = :id";
		$update_prep = self::$db->prepare($update);
		$update_prep->bindParam(':id_evenement', $this->evenement->id, \PDO::PARAM_INT);
		$update_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $update_prep->bindParam(':distance', $this->distance, \PDO::PARAM_INT);
        $update_prep->bindParam(':dateheure', date_format(date_create($this->dateheure),"Y-m-d H:i:s"), \PDO::PARAM_STR);
		$update_prep->bindParam(':id', $this->id, \PDO::PARAM_INT);
		if($update_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	protected function insert()
	{
		$insert = "INSERT INTO epreuve VALUES(:id, :nom, :distance, :dateheure, :id_evenement)";
        $insert_prep = self::$db->prepare($insert);
		$insert_prep->bindParam(':id', $this->id, \PDO::PARAM_INT);
		$insert_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $insert_prep->bindParam(':distance', $this->distance, \PDO::PARAM_INT);
        $insert_prep->bindParam(':dateheure',  date_format(date_create($this->dateheure),"Y-m-d H:i:s"), \PDO::PARAM_STR);
		$insert_prep->bindParam(':id_evenement', $this->evenement_id, \PDO::PARAM_INT);
		if($insert_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function save()
	{
		if(is_null($this->id)){
			return $this->insert();
		}
		else{
			return $this->update();
		}
	}
	
	public function delete()
	{
		$delete = "DELETE FROM epreuve WHERE id = :id";
        $delete_prep = self::$db->prepare($delete);
		$delete_prep->bindParam(':id', $this->id, \PDO::PARAM_INT);
		if($delete_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public static function findById($leId)
	{
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
        $selectById = "SELECT * FROM epreuve WHERE id = :id";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id', $leId, \PDO::PARAM_INT);
        $selectById_prep->execute();
		$obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new epreuve();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->distance  = $ligne['distance'];
			$obj->dateheure = date_create($ligne['dateheure']);
			
			$obj->evenement = \sportnet\model\evenement::findById($ligne['id_evenement']);
		}
		return $obj;
	}
	
	public static function findAll()
	{
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
        $select = "SELECT * FROM epreuve";
        $select_prep = self::$db->prepare($select);
        $select_prep->execute();
		$tab = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new epreuve();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->distance  = $ligne['distance'];
			$obj->dateheure = date_create($ligne['dateheure']);

			$obj->evenement = \sportnet\model\evenement::findById($ligne['id_evenement']);
			
			$tab[] = $obj;
		}
		return $tab;
	}
	
	public static function findByName($leNom)
	{
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
        $selectByName = "SELECT * FROM evenement WHERE nom = :nom";
        $selectByName_prep = self::$db->prepare($selectByName);
        $selectByName_prep->bindParam(':nom', $leNom, \PDO::PARAM_STR);
        $selectByName_prep->execute();
		$obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new epreuve();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->distance  = $ligne['distance'];
			$obj->dateheure = date_create($ligne['dateheure']);
			
			$obj->evenement = \sportnet\model\evenement::findById($ligne['id_evenement']);
		}
		return $obj;
	}
	
	public function getEvenement()
	{
		$select = "SELECT * FROM evenement where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->id, \PDO::PARAM_INT);
        $select_prep->execute();
		$obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new evenement();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->description  = $ligne['description'];
			$obj->etat = $ligne['etat'];
			$obj->dateheureLimiteInscription = date_create($ligne['dateheureLimiteInscription']);
			$obj->tarif = $ligne['tarif'];
			$obj->discipline = \sportnet\model\discipline::findById($ligne['id_discipline']);
			$obj->organisateur = \sportnet\model\organisateur::findById($ligne['id_organisateur']);
		}
		return $obj;
	}
}
?>