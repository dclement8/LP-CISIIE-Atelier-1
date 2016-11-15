<?php
namespace sportnet\model;
class organisateur extends AbstractModel {
	private  $id;
	private  $login;
	private  $mdp;
	private  $nom;
	private  $prenom;
	private  $adresse;
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
		$update = "UPDATE organisateur SET login = :login, mdp = :mdp, nom = :nom, prenom = :prenom, adresse = :adresse, cp = :cp, ville = :ville, tel = :tel WHERE id = :id";
		$update_prep = self::$db->prepare($update);
		$update_prep->bindParam(':login', $this->login, \PDO::PARAM_STR);
		$update_prep->bindParam(':mdp', $this->mdp, \PDO::PARAM_STR);
		$update_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $update_prep->bindParam(':prenom', $this->prenom, \PDO::PARAM_STR);
        $update_prep->bindParam(':adresse', $this->adresse, \PDO::PARAM_STR);
        $update_prep->bindParam(':cp', $this->cp, \PDO::PARAM_INT);
		$update_prep->bindParam(':ville', $this->ville, \PDO::PARAM_STR);
		$update_prep->bindParam(':tel', $this->tel, \PDO::PARAM_INT);
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
		$insert = "INSERT INTO organisateur VALUES(:id, :login, :mdp, :nom, :prenom, :adresse, :cp, :ville, :tel)";
        $insert_prep = self::$db->prepare($insert);
		$insert_prep->bindParam(':id', $this->id, \PDO::PARAM_INT);
		$insert_prep->bindParam(':login', $this->login, \PDO::PARAM_STR);
		$insert_prep->bindParam(':mdp', $this->mdp, \PDO::PARAM_STR);
		$insert_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
        $insert_prep->bindParam(':prenom', $this->prenom, \PDO::PARAM_STR);
        $insert_prep->bindParam(':adresse', $this->adresse, \PDO::PARAM_STR);
        $insert_prep->bindParam(':cp', $this->cp, \PDO::PARAM_INT);
		$insert_prep->bindParam(':ville', $this->ville, \PDO::PARAM_STR);
		$insert_prep->bindParam(':tel', $this->tel, \PDO::PARAM_INT);
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
		$delete = "DELETE FROM organisateur WHERE id = :id";
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
        $selectById = "SELECT * FROM organisateur WHERE id = :id";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id', $leId, \PDO::PARAM_INT);
        if ($selectById_prep->execute()) {
            return $selectById_prep->fetchObject(__CLASS__);
            return null;
        }
	}
	
	public static function findAll()
	{
		$db = ConnectionFactory::makeConnection();
        $select = "SELECT * FROM organisateur";
        $resultat = self::$db->query($select);
        if ($resultat) {
            return $resultat->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
        }else{
            return null;
        }
	}
	
	public static function findByLogin($leLogin)
	{
		$db = ConnectionFactory::makeConnection();
        $selectByName = "SELECT * FROM organisateur WHERE login = :login";
        $selectByName_prep = self::$db->prepare($selectById);
        $selectByName_prep->bindParam(':login', $leLogin, \PDO::PARAM_STR);
        if ($selectByName_prep->execute()) {
            return $selectByName_prep->fetchObject(__CLASS__);
        }else{
            return null;
        }
	}
	
	public function getEvenements()
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