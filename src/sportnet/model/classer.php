<?php
namespace sportnet\model;
class classer extends AbstractModel {
	protected  $position;
	protected  $temps;
	
	// Objets associés
	protected $epreuve;
	protected $participant;
	
	
	public function __construct()
	{
		$connect = new \sportnet\utils\ConnectionFactory();
		$connect->setConfig("conf/config.ini");
		self::$db = $connect->makeConnection();
		self::$db->query("SET CHARACTER SET utf8");
	}
	
	protected function update()
	{
		$update = "UPDATE classer SET position = :position, temps = :temps WHERE id_epreuve = :id_epreuve AND id_participant = :id_participant";
		$update_prep = self::$db->prepare($update);
		
		$idEpreuve = $this->epreuve->id;
		$idParticipant = $this->participant->id;
		
		$update_prep->bindParam(':position', $this->position, \PDO::PARAM_INT);
		$update_prep->bindParam(':temps', $this->temps, \PDO::PARAM_INT);
		$update_prep->bindParam(':id_epreuve', $idEpreuve, \PDO::PARAM_INT);
		$update_prep->bindParam(':id_participant', $idParticipant, \PDO::PARAM_INT);
		if($update_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	protected function insert()
	{
		$insert = "INSERT INTO classer VALUES(:position, :temps, :id_epreuve, :id_participant)";
        $insert_prep = self::$db->prepare($insert);
		
		$idEpreuve = $this->epreuve->id;
		$idParticipant = $this->participant->id;
		
		$insert_prep->bindParam(':position', $this->position, \PDO::PARAM_INT);
		$insert_prep->bindParam(':temps', $this->temps, \PDO::PARAM_INT);
		$insert_prep->bindParam(':id_epreuve', $idEpreuve, \PDO::PARAM_INT);
		$insert_prep->bindParam(':id_participant', $idParticipant, \PDO::PARAM_INT);
		if($insert_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function save()
	{
		return $this->insert();
	}
	
	public function delete()
	{
		$delete = "DELETE FROM classer WHERE id_epreuve = :id_epreuve AND id_participant = :id_participant";
        $delete_prep = self::$db->prepare($delete);
		
		$idEpreuve = $this->epreuve->id;
		$idParticipant = $this->participant->id;
		
		$delete_prep->bindParam(':id_epreuve', $idEpreuve, \PDO::PARAM_INT);
		$delete_prep->bindParam(':id_participant', $idParticipant, \PDO::PARAM_INT);
		if($delete_prep->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	public static function findById($leIdEpreuve)
	{
		if(self::$db == null)
		{
			$connect = new \sportnet\utils\ConnectionFactory();
			$connect->setConfig("conf/config.ini");
			self::$db = $connect->makeConnection();
			self::$db->query("SET CHARACTER SET utf8");
		}
        $selectById = "SELECT * FROM classer WHERE id_epreuve = :id_epreuve ORDER BY position ASC";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id_epreuve', $leIdEpreuve, \PDO::PARAM_INT);
        $selectById_prep->execute();
		$tab = null;
		while ($ligne = $selectById_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new classer();

			$obj->position = $ligne['position'];
			$obj->temps = $ligne['temps'];

			$obj->epreuve = \sportnet\model\epreuve::findById($ligne['id_epreuve']);
			$obj->participant = \sportnet\model\participant::findById($ligne['id_participant']);

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
        $select = "SELECT * FROM classer";
        $select_prep = self::$db->prepare($select);
        $selectById_prep->execute();
		$tab = null;
		while ($ligne = $selectById_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new classer();

			$obj->position = $ligne['position'];
			$obj->temps = $ligne['temps'];

			$obj->epreuve = \sportnet\model\epreuve::findById($ligne['id_epreuve']);
			$obj->participant = \sportnet\model\participant::findById($ligne['id_participant']);

			$tab[] = $obj;
		}
		return $tab;
	}
	
	public function getEpreuve()
	{
		$select = "SELECT * FROM epreuve where id = :id";
        $select_prep = self::$db->prepare($select);
		
		$idEpreuve = $this->epreuve->id;
		
		$select_prep->bindParam(':id', $idEpreuve, \PDO::PARAM_INT);
        $select_prep->execute();
		$obj = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new epreuve();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->distance  = $ligne['distance'];
			$obj->dateheure = date_create($ligne['dateheure']);

			$obj->epreuve = \sportnet\model\epreuve::findById($ligne['id_epreuve']);
		}
		return $obj;
	}
	
	public function getParticipant()
	{
		$select = "SELECT * FROM participant where id = :id";
        $select_prep = self::$db->prepare($select);
		
		$idParticipant = $this->participant->id;
		
		$select_prep->bindParam(':id', $idParticipant, \PDO::PARAM_INT);
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
}
?>