

<?php
// file: model/UserMapper.php
//include('Mail.php');

require_once(__DIR__."/../core/PDOConnection.php");

/**
* Class UserMapper
*
* Database interface for User entities
*
* @author lipido <lipido@gmail.com>
*/
class UserMapper {

	/**
	* Reference to the PDO connection
	* @var PDO
	*/
	private $db;

	public function __construct() {
		$this->db = PDOConnection::getInstance();
	}


	/**
	* Saves a User into the database
	*
	* @param User $user The user to be saved
	* @throws PDOException if a database error occurs
	* @return void
	*/
	public function save($user) {
		$stmt = $this->db->prepare("INSERT INTO usuario values (?,?,?)");
		$result = $stmt->execute(array($user->getAlias(), $user->getPasswd(), $user->getEmail()));
		if ($result) {
			echo "\nUsuario guardado correctamente en la base de datos.";

			/*$recipients = $user->getEmail();
			$headers['From']    = 'ivan.rasela.verin@gmail.com';
			$headers['To']      = $user->getEmail();
			$headers['Subject'] = 'switchs has been switchsed on!';
			$body = 'The switchs xxxx you are subscribed to has been powered on!!';
			$params['host'] = '172.23.208.1'; //esta es la IP de la máquina host cuando se usa docker, allí hay un fakesmtp
			$params['port'] = '2525'; // puerto del fakesmtp
			// Create the mail object using the Mail::factory method
			$mail_object = Mail::factory('smtp', $params);
			$mail_object->send($recipients, $headers, $body);*/
		} else {
			echo "Error al guardar el usuario en la base de datos.";
		}
	}

	/**
	* Checks if a given username is already in the database
	*
	* @param string $username the username to check
	* @return boolean true if the username exists, false otherwise
	*/
	public function aliasExists($alias) {
		$stmt = $this->db->prepare("SELECT count(alias) FROM usuario where alias=?");
		$stmt->execute(array($alias));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}

	public function emailExists($email) {
		$stmt = $this->db->prepare("SELECT count(email) FROM usuario where email=?");
		$stmt->execute(array($email));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
		//En php, si no se especifica un valor de retorno, la función
		//	devolverá automáticamente null al final de su ejecución. 
	}



	/**
	* Checks if a given pair of alias/password/email exists in the database
	*
	* @param string $username the username
	* @param string $passwd the password
	* @return boolean true the username/passwrod exists, false otherwise.
	*/
	public function isValidUser($alias, $passwd) {
		$stmt = $this->db->prepare("SELECT count(alias) FROM usuario where alias=? and passwd=?");
		$stmt->execute(array($alias, $passwd));

		if ($stmt->fetchColumn() > 0) {
			return true;
		}
	}

	public function findByAlias($alias){
		$stmt = $this->db->prepare("SELECT * FROM usuario WHERE alias=?");
		$stmt->execute(array($alias));
		$userArray = $stmt->fetch(PDO::FETCH_ASSOC);

		if($userArray != null){
			$user = new User($userArray['alias'], $userArray['passwd'], $userArray['email']);
		}else{
			return NULL;
		}

	}

	public function deleteUser($alias, $passwd){
		$stmt = $this->db->prepare("DELETE FROM usuario WHERE alias = ?");
		$stmt->execute(array($alias));
		if(!$this->isValidUser($alias, $passwd)){
			//Se ha eliminado correctamente
			return true;
		}else{
			return false;
		}

	}

	public function update($user, $alias, $passwd){
		//Elimina el usuario con ese alias
		if($this->deleteUser($alias, $passwd)){
			//Crea el usuario 
			$this->save($user);
			return true;
		}else{
			return false;
		}

	}



}