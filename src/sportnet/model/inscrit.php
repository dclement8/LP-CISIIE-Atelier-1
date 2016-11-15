<?php
namespace sportnet\model;
class inscrit extends AbstractModel {
	private  $dossard;
	
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
		$update = ("UPDATE inscrit SET dossard = :dossard WHERE id = :id");
		$update_prep = self::$db->prepare($update);
		$update_prep->bindParam(':dossard', $this->dossard, \PDO::PARAM_STR);
		$update_prep->bindParam(':id', $this->participant->id, \PDO::PARAM_INT);
		if($update_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	protected function insert()
	{
		$insert = "INSERT INTO organisateur VALUES(:dossard)";
        $insert_prep = self::$db->prepare($insert);
		$insert_prep->bindParam(':dossard', $this->dossard, \PDO::PARAM_INT);
		if($insert_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function save()
	{
		if(is_null($this->participant->id)){
			return $this->insert();
		}
		else{
			return $this->update();
		}
	}
	
	public function delete()
	{
		$delete = "DELETE FROM inscrit WHERE id = :id";
        $delete_prep = self::$db->prepare($delete);
		$delete_prep->bindParam(':id', $this->participant->id, \PDO::PARAM_INT);
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
        $selectById = "SELECT * FROM inscrit WHERE id = :id";
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
        $select = "SELECT * FROM inscrit";
        $resultat = self::$db->query($select);
        if ($resultat) {
            return $resultat->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        }else{
            return null;
        }
	}
	
	public function getEpreuve()
	{
		$select = "SELECT * FROM epreuve where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->epreuve->id, \PDO::PARAM_INT);
        if($select_prep->execute()){
            return $select_prep->fetchObject(epreuve::class);
        }else{
            return null;
        }
	}
	
	public function getParticipant()
	{
		$select = "SELECT * FROM participant where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->participant->id, \PDO::PARAM_INT);
        if($select_prep->execute()){
            return $select_prep->fetchObject(User::class);
        }else{
            return null;
        }
	}
}
?>