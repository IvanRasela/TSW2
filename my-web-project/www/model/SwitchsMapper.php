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
	
	public function findIfSuscribe($user) {
		$stmt = $this->db->prepare("SELECT * FROM Suscriptor WHERE Suscriptor.alias=?");
		$stmt->execute(array($user));
		$switchs_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		$switches = array();
	
		foreach ($switchs_db as $switchData) {
			$sw = $this->findById($switchData["Public_UUID"]);
			array_push($switches, $sw);
		}
		
		if($switches!=NULL){
			return $switches;
		}
		else{
			return NULL;
		}
	
		
	}

	public function findAllSuscribers($user) {
		$stmt = $this->db->prepare("SELECT * FROM Suscriptor WHERE Suscriptor.alias=?");
		$stmt->execute(array($user));
		$suscribers_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		$suscribers = array();
		
		foreach ($suscribers_db as $switchData) {
			$sw = $this->findById($switchData["Public_UUID"]);
			//$alias = new User($sw["AliasUser"]);
			$alias = new User($sw["AliasUser"]);
			$switch = new Switchs(
				$sw["SwitchName"],
				$sw["Private_UUID"],
				$sw["Public_UUID"],
				$alias,
				$sw["DescriptionSwitch"],
				$sw["LastTimePowerOn"],
				$sw["MaxTimePowerOn"]);
			array_push($suscribers, $sw);
		}
	
		return $suscribers;
	}


	//Devuelve un array de switches
	public function findById($uuid){
		$stmt = $this->db->prepare("SELECT * FROM Switchs WHERE Public_UUID=?");
		$stmt->execute(array($uuid));
		$switchs = $stmt->fetch(PDO::FETCH_ASSOC);

		if($switchs != null) {
			$result = new Switchs(
			$switchs["SwitchName"],
			$switchs["Private_UUID"],
			$switchs["Public_UUID"],
			new User($switchs["AliasUser"]),
			$switchs["DescriptionSwitch"],
			$switchs["LastTimePowerOn"],
			$switchs["MaxTimePowerOn"]);
			
			return $result;
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
		public function save(Switchs $switch) {
			$switch->setPublic_UUID($this->generateUUID($switch->getAliasUser()->getAlias()));
			$switch->setPrivate_UUID($this->generateUUID($switch->getAliasUser()->getAlias()));
			$stmt = $this->db->prepare("INSERT INTO Switchs values (?,?,?,?,?,?,?)");

			$stmt->execute(array(
				$switch->getSwitchName(), 
				$switch->getPrivate_UUID(), 
				$switch->getPublic_UUID(),
				$switch->getDescriptionSwitch(),
				$switch->getAliasUser()->getAlias(),
				NULL,
				$switch->getMaxTimePowerOn()));
			if ($stmt) {
				echo "\Switch guardado correctamente en la base de datos.";
			} else {
				echo "Error al guardar el switch en la base de datos.";
			}
		}

		public function generateUUID($alias){
			// Genera una clave privada única basada en el alias y la marca de tiempo
			$privateKey = md5($alias . microtime());

			// Obtiene el timestamp actual
			$timestamp = time();
		
			// Convierte el timestamp a hexadecimal y le da un formato específico
			$timestampHex = dechex($timestamp);
			$timestampHex = str_pad($timestampHex, 8, '0', STR_PAD_LEFT);
		
			// Convierte la parte de la clave privada a hexadecimal y le da un formato específico
			$privateKeyHex = substr($privateKey, 0, 16);
		
			// Formatea el UUID para cumplir con varchar(36)
			$uuid = substr($timestampHex, 0, 8) . '-' .
					substr($timestampHex, 8, 4) . '-' .
					substr($timestampHex, 12, 4) . '-' .
					substr($privateKeyHex, 0, 4) . '-' .
					substr($privateKeyHex, 4, 12);
		
			return $uuid;
		}

		public function suscribeTo(Switchs $switch) {
			$stmt = $this->db->prepare("INSERT INTO Suscriptor(alias, Public_UUID) values (?,?)");
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