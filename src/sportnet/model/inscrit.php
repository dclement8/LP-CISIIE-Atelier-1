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
		$update = "UPDATE inscrit SET dossard = :dossard WHERE id_epreuve = :id_epreuve AND id_participant = :id_participant";
		$update_prep = self::$db->prepare($update);
		$update_prep->bindParam(':dossard', $this->dossard, \PDO::PARAM_STR);
		$update_prep->bindParam(':id_epreuve', $this->epreuve->id, \PDO::PARAM_INT);
		$update_prep->bindParam(':id_participant', $this->participant->id, \PDO::PARAM_INT);
		if($update_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	protected function insert()
	{
		$insert = "INSERT INTO inscrit VALUES(:dossard, :id_epreuve, :id_participant)";
        $insert_prep = self::$db->prepare($insert);
		$insert_prep->bindParam(':dossard', $this->dossard, \PDO::PARAM_INT);
		$insert_prep->bindParam(':id_epreuve', $this->epreuve->id, \PDO::PARAM_INT);
		$delete_prep->bindParam(':id_participant', $this->participant->id, \PDO::PARAM_INT);
		if($insert_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function save()
	{
		if(is_null($this->epreuve)){
			return $this->insert();
		}
		else{
			return $this->update();
		}
	}
	
	public function delete()
	{
		$delete = "DELETE FROM inscrit WHERE id_epreuve = :id_epreuve AND id_participant = :id_participant";
        $delete_prep = self::$db->prepare($delete);
		$delete_prep->bindParam(':id_epreuve', $this->epreuve->id, \PDO::PARAM_INT);
		$delete_prep->bindParam(':id_participant', $this->participant->id, \PDO::PARAM_INT);
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
        $selectById = "SELECT * FROM inscrit WHERE id_epreuve = :id_epreuve";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id_epreuve', $leId, \PDO::PARAM_INT);
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
            return $select_prep->fetchObject(participant::class);
        }else{
            return null;
        }
	}

	public static function getMaxDossard($leIdEpreuve)
	{
		$select = "SELECT MAX(dossard) as leMax FROM inscrit WHERE id_epreuve = :id_epreuve";
		$select_prep = self::$db->prepare($select);
		$select_prep->bindParam(":id_epreuve", $leIdEpreuve, \PDO::PARAM_INT);
		if($select_prep->execute()) {
            return $resultat->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        }else{
            return null;
        }
	}
}
?>