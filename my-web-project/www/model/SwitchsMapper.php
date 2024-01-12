<?php
// file: model/SwitchMapper.php
require_once(__DIR__."/../core/PDOConnection.php");
require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/Switchs.php");

/**
* Class SwitchMapper
*
* Database interface for Switch entities
*
* @author lipido <lipido@gmail.com>
*/
class SwitchsMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}

	/**
	* Retrieves all switches
	*
	* Note: Comments are not added to the Switch instances
	*
	* @throws PDOException if a database error occurs
	* @return mixed Array of switchs instances (without comments)
	*/

	//"error": "array_push(): Argument #1 ($array) must be of type array, null given"
	public function findAll($user) {
		//$stmt = $this->db->query("SELECT * FROM Switchs");

		$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Switchs.AliasUser=?");
		$stmt->execute(array($user));
		$switches_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		$switches = array(); 
		
		foreach ($switches_db as $switch) {
			$alias = new User($switch["AliasUser"]);
			array_push($switches, new Switchs($switch["SwitchName"], $switch["Private_UUID"], $switch["Public_UUID"], $alias));
		}
	
		return $switches;
	}
	
	public function findAllSuscribers($user) {
		$stmt = $this->db->prepare("SELECT * FROM Suscriptor WHERE Suscriptor.AliasUser=?");
		$stmt->execute(array($user));
		$suscribers_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		$suscribers = array();
		
		foreach ($suscribers_db as $switchData) {
			$sw = $this->findById($switchData["Public_UUID"]);
			$alias = new User($sw["AliasUser"]);
			array_push($suscribers, new Switchs($sw["SwitchName"], $sw["Public_UUID"], $alias));
		}
	
		return $suscribers;
	}

	/*public function findIfSuscribe($user) {
		$stmt = $this->db->prepare("SELECT * FROM Suscriptor WHERE Suscriptor.SuscriptorAlias=?");
		$stmt->execute(array($user));
		$switchs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		$switches = array();
	
		foreach ($switchs_db as $switchData) {
			$sw = $this->findById($switchData["Public_UUID"]);
			if ($sw != NULL) {
				$alias = new User($sw->getAliasUser());
				$switch = new Switchs(
					$sw->getSwitchName(),
					$sw->getPrivate_UUID(),
					$sw->getPublic_UUID(),
					$alias
				);
				array_push($switches, $switch);
			}
		}
	
		return $switches;
	}*/

	/**
	* Loads a Switch from the database given its id
	*
	* Note: Comments are not added to the Switch
	*
	* @throws PDOException if a database error occurs
	* @return Switch The switchs instances (without comments). NULL
	* if the Switch is not found
	*/
	public function findById($publicuuid){
		$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Switchs.Public_UUID=?");
		$stmt->execute(array($publicuuid));
		$switchs = $stmt->fetch(PDO::FETCH_ASSOC);
		if($switchs != null) {
			return $switchs;
		} else {
			return NULL;
		}
	}






	public function findByIdPrivate($uuid){
		$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Private_UUID=?");
		$stmt->execute(array($uuid));
		$switchs = $stmt->fetch(PDO::FETCH_ASSOC);

		if($switchs != null) {
			$switch = new Switchs(
			$switchs["SwitchName"],
			$switchs["Private_UUID"],
			$switchs["Public_UUID"],
			new User($switchs["AliasUser"]),
			$switchs["DescriptionSwitch"],
			$switchs["LastTimePowerOn"],
			$switchs["MaxTimePowerOn"]);
		} else {
			return NULL;
		}
		return $switch;
	}
		/**
		* Saves a Switch into the database
		*
		* @param Switch $switch The switch to be saved
		* @throws PDOException if a database error occurs
		* @return int The mew switch id
		*/
		public function save(Switchs $switchs) {
			$stmt = $this->db->prepare("INSERT INTO Switchs(SwitchName, Private_UUID, Public_UUID, LastTimePowerOn, MaxTimePowerOn, DescriptionSwitch, AliasUser) values (?,?,?,?,?,?,?)");
			$stmt->execute(array($switchs->getSwitchName(), $switchs->getPrivate_UUID(), $switchs->getPublic_UUID(),$switchs->getLastTimePowerOn(),$switchs->getMaxTimePowerOn(),$switchs->getDescriptionSwitch(),$switchs->getAliasUser()->getAlias()));
			return $this->db->lastInsertId();
		}

		public function suscribeTo(Switchs $switch) {
			$stmt = $this->db->prepare("INSERT INTO Suscriptor(SuscriptorAlias, Public_UUID) values (?,?)");
			$stmt->execute(array($switchs->getSwitchName(),$switchs->getPublic_UUID()));
			return $this->db->lastInsertId();
		}

		/**
		* Updates a Switch in the database
		*
		* @param Switch $switch The switch to be updated
		* @throws PDOException if a database error occurs
		* @return void
		*/
		public function update(Switchs $switch) {
			$stmt = $this->db->prepare("UPDATE switches set title=?, content=? where id=?");
			$stmt->execute(array($switch->getTitle(), $switch->getContent(), $switch->getId()));
		}

		/**
		* Deletes a Switch into the database
		*
		* @param switchs $switchs The switchs to be deleted
		* @throws PDOException if a database error occurs
		* @return void
		*/
		public function delete(Switchs $switchs) {
			$stmt = $this->db->prepare("DELETE from Switchs WHERE Private_UUID=?");
			$stmt->execute(array($switchs->getPrivate_UUID()));
		}

		public function desuscribeTo(Switchs $switchs) {
			$stmt = $this->db->prepare("DELETE from Suscriptor WHERE Public_UUID=?");
			$stmt->execute(array($switchs->getPublic_UUID()));
		}

		public function createUUID(){
			do {
				$miUUID = $this->generarUUID();
			} while ($this->itsOnUse($miUUID));
			
    		return $miUUID;
		}

		public function generarUUID() {
			return sprintf(
				'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				mt_rand(0, 0xffff), mt_rand(0, 0xffff),
				mt_rand(0, 0xffff),
				mt_rand(0, 0x0fff) | 0x4000,
				mt_rand(0, 0x3fff) | 0x8000,
				mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
			);
		}

		public function itsOnUse($uuid){
			$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Public_UUID=?");
			$stmt->execute(array($uuid));
			$switchs = $stmt->fetch(PDO::FETCH_ASSOC);

			$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Private_UUID=?");
			$stmt->execute(array($uuid));
			$switchs = $stmt->fetch(PDO::FETCH_ASSOC);
			if($switchs != null) {
				return true;
			} else {
				return false;
			}
		}

	}