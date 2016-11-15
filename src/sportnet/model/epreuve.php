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
        $update_prep->bindParam(':dateheure', $this->dateheure, \PDO::PARAM_STR);
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
        $insert_prep->bindParam(':dateheure', $this->dateheure, \PDO::PARAM_STR);
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
		$db = ConnectionFactory::makeConnection();
        $selectById = "SELECT * FROM epreuve WHERE id = :id";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id', $leId, \PDO::PARAM_INT);
        if ($selectById_prep->execute()) {
            return $selectById_prep->fetchObject(__CLASS__);
        }else{
            return null;
        }
	}
	
	public static function findAll()
	{
		$db = ConnectionFactory::makeConnection();
        $select = "SELECT * FROM epreuve";
        $resultat = self::$db->query($select);
        if ($resultat) {
            return $resultat->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        }else{
            return null;
        }
	}
	
	public static function findByName($leNom)
	{
		$db = ConnectionFactory::makeConnection();
        $selectByName = "SELECT * FROM evenement WHERE nom = :nom";
        $selectByName_prep = self::$db->prepare($selectById);
        $selectByName_prep->bindParam(':nom', $leNom, \PDO::PARAM_STR);
        if ($selectByName_prep->execute()) {
            return $selectByName_prep->fetchObject(__CLASS__);
        }else{
            return null;
        }
	}
	
	public function getEvenement()
	{
		$select = "SELECT * FROM evenement where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->id, \PDO::PARAM_INT);
        if($select_prep->execute()){
            return $select_prep->fetchObject(evenement::class);
        }else{
            return null;
        }
	}
}
?>