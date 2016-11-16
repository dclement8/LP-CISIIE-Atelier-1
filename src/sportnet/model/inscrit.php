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
	
	public function __get($attr_name)
	{
        if (property_exists( $this, $attr_name))
		{
			return $this->$attr_name;
		}
		else
		{
			$emess = $this . ": unknown member $attr_name (__get)";
			throw new \Exception($emess);
		}
    }
	
	public function __set($attr_name, $attr_val)
	{
        if (property_exists( $this, $attr_name))
		{
			$this->$attr_name=$attr_val;
		} 
        else
		{
            $emess = $this . ": unknown member $attr_name (__set)";
            throw new \Exception($emess);
        }
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
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
        $selectById = "SELECT * FROM inscrit WHERE id_epreuve = :id_epreuve";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id_epreuve', $leId, \PDO::PARAM_INT);
        $selectById_prep->execute();
		$tab = null;
		while ($ligne = $selectById_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new inscrit();

			$obj->dossard = $ligne['dossard'];
			
			$obj->participant = \sportnet\model\participant::findById($ligne['id_participant']);
			$obj->epreuve = \sportnet\model\epreuve::findById($ligne['id_epreuve']);

			$tab[] = $obj;
		}
		return $tab;
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
        $select = "SELECT * FROM inscrit";
        $select_prep = self::$db->prepare($select);
        $select_prep->execute();
		$tab = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new inscrit();

			$obj->dossard = $ligne['dossard'];
			
			$obj->participant = \sportnet\model\participant::findById($ligne['id_participant']);
			$obj->epreuve = \sportnet\model\epreuve::findById($ligne['id_epreuve']);

			$tab[] = $obj;
		}
		return $tab;
	}
	
	public function getEpreuve()
	{
		$select = "SELECT * FROM epreuve where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->epreuve->id, \PDO::PARAM_INT);
        $select_prep->execute();
		$obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new epreuve();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->distance  = $ligne['distance'];
			$obj->dateheure = $ligne['dateheure'];
			
			$obj->evenement = \sportnet\model\evenement::findById($ligne['id_evenement']);
		}
		return $obj;
	}
	
	public function getParticipant()
	{
		$select = "SELECT * FROM participant where id = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->participant->id, \PDO::PARAM_INT);
        $select_prep->execute();
		$obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new participant();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->prenom = $ligne['prenom'];
			$obj->rue = $ligne['rue'];
			$obj->cp = $ligne['cp'];
			$obj->ville = $ligne['ville'];
			$obj->tel = $ligne['tel'];
		}
		return $obj;
	}

	public static function getMaxDossard($leIdEpreuve)
	{
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
		$select = "SELECT MAX(dossard) as leMax FROM inscrit WHERE id_epreuve = :id_epreuve";
		$select_prep = self::$db->prepare($select);
		$select_prep->bindParam(":id_epreuve", $leIdEpreuve, \PDO::PARAM_INT);
		$select_prep->execute();
		$obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = $ligne['leMax'];
		}
		return $obj;
	}
}
?>