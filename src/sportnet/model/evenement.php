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
		$update = "UPDATE evenement SET nom = :nom, description = :description, etat = :etat, dateheureLimiteInscription = :dateheureLimiteInscription,
		tarif = :tarif, id_discipline = :id_discipline, id_organisateur = :id_organisateur WHERE id = :id";
		$update_prep = self::$db->prepare($update);
		$update_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $update_prep->bindParam(':description', $this->description, \PDO::PARAM_STR);
        $update_prep->bindParam(':etat', $this->etat, \PDO::PARAM_INT);
        $update_prep->bindParam(':dateheureLimiteInscription', $this->dateheureLimiteInscription, \PDO::PARAM_STR);
		$update_prep->bindParam(':tarif', $this->ville, \PDO::PARAM_STR);
		$update_prep->bindParam(':id_discipline', $this->discipline->id, \PDO::PARAM_INT);
		$update_prep->bindParam(':id_organisateur', $this->organisateur->id, \PDO::PARAM_INT);
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
		$insert = "INSERT INTO evenement VALUES(:id, :login, :mdp, :nom, :prenom, :adresse, :cp, :ville, :tel, :id_discipline, :id_organisateur)";
        $insert_prep = self::$db->prepare($insert);
		$insert_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $insert_prep->bindParam(':description', $this->description, \PDO::PARAM_STR);
        $insert_prep->bindParam(':etat', $this->etat, \PDO::PARAM_INT);
        $insert_prep->bindParam(':dateheureLimiteInscription', $this->dateheureLimiteInscription, \PDO::PARAM_STR);
		$insert_prep->bindParam(':tarif', $this->ville, \PDO::PARAM_STR);
		$insert_prep->bindParam(':id_discipline', $this->discipline->id, \PDO::PARAM_INT);
		$insert_prep->bindParam(':id_organisateur', $this->organisateur->id, \PDO::PARAM_INT);
		$insert_prep->bindParam(':id', $this->id, \PDO::PARAM_INT);
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
		$delete = "DELETE FROM evenement WHERE id = :id";
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
        $selectById = "SELECT * FROM evenement WHERE id = :id";
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
        $select = "SELECT * FROM evenement";
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
	
	public function getEpreuves()
	{
		$select = "SELECT * FROM epreuve where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->id, \PDO::PARAM_INT);
        if($select_prep->execute()){
            return $select_prep->fetchObject(epreuve::class);
        }else{
            return null;
        }
	}
	
	public function getOrganisateur()
	{
		$select = "SELECT * FROM organisateur where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->id, \PDO::PARAM_INT);
        if($select_prep->execute()){
            return $select_prep->fetchObject(organisateur::class);
        }else{
            return null;
        }
	}
	
	public function getDiscipline()
	{
		$select = "SELECT * FROM discipline where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->id, \PDO::PARAM_INT);
        if($select_prep->execute()){
            return $select_prep->fetchObject(discipline::class);
        }else{
            return null;
        }
	}
}
?>