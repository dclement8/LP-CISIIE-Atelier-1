<?php
namespace sportnet\model;
class discipline extends AbstractModel {
	private  $id;
	private  $nom;

	
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
	
	protected function update()
	{
		$update = "UPDATE discipline SET nom = :nom WHERE id = :id";
		$update_prep = self::$db->prepare($update);
		$update_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
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
		$insert = "INSERT INTO discipline VALUES(:id, :nom)";
        $insert_prep = self::$db->prepare($insert);
		$insert_prep->bindParam(':nom', $this->nom, \PDO::PARAM_STR);
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
		$delete = "DELETE FROM discipline WHERE id = :id";
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
        $selectById = "SELECT * FROM discipline WHERE id = :id";
        $selectById_prep = self::$db->prepare($selectById);
        $selectById_prep->bindParam(':id', $leId, \PDO::PARAM_INT);
        $selectById_prep->execute();
		$obj = null;
		while ($ligne = $selectById_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new discipline();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
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
        $select = "SELECT * FROM discipline";
        $select_prep = self::$db->prepare($select);
        $select_prep->execute();
		$tab = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new discipline();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];

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
        $selectByName = "SELECT * FROM discipline WHERE nom = :nom";
        $selectByName_prep = self::$db->prepare($selectById);
        $selectByName_prep->bindParam(':nom', $leNom, \PDO::PARAM_STR);
        $selectByName_prep->execute();
		$obj = null;
		while ($ligne = $selectByName->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new discipline();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
		}
		return $obj;
	}
	
	public function getEvenements()
	{
		$select = "SELECT * FROM evenement where id_discipline = :id";
        $select_prep = self::$db->prepare($select);
        $select_prep->bindParam(":id", $this->id, \PDO::PARAM_INT);
        $select_prep->execute();
		$tab = null;
		while ($ligne = $select_prep->fetch(\PDO::FETCH_ASSOC)) {
			$obj = new evenement();

			$obj->id = $ligne['id'];
			$obj->nom = $ligne['nom'];
			$obj->description  = $ligne['description'];
			$obj->etat = $ligne['etat'];
			$obj->dateheureLimiteInscription = $ligne['dateheureLimiteInscription'];
			$obj->tarif = $ligne['tarif'];
			$obj->discipline = \sportnet\model\discipline::findById($ligne['id_discipline']);
			$obj->organisateur = \sportnet\model\organisateur::findById($ligne['id_organisateur']);

			$tab[] = $obj;
		}
		return $tab;
	}
}
?>