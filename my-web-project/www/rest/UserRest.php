UserRest.php
<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");
require_once(__DIR__."/BaseRest.php");
require_once(__DIR__."/URIDispatcher.php");

/**
* Class UserRest
*
* It contains operations for adding and check users credentials.
* Methods gives responses following Restful standards. Methods of this class
* are intended to be mapped as callbacks using the URIDispatcher class.
*
*/
class UserRest extends BaseRest {

	private $userMapper;

	public function __construct() {
		parent::__construct();

		$this->userMapper = new UserMapper();
		echo("Constructor");
	}
	
	//FALTA COMPROBAR
	//Método HTTP GET /account para el inicio de sesión
	//	Data IN: {"alias"}
	//	Data OUT: {HTTP 200-OK, set cookie "bearer_token" / HTTP 403 Forbidden}
	public function login($alias) {
		
		$currentLogged = parent::authenticateUser();
		if ($currentLogged->getAlias() != $alias) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden'); //el servidor entiende la solicitud pero se niega a cumplirla
			echo("You are not authorized to login as anyone but you");
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 201 Ok'); 
			echo("Hello ".$alias);
		}
	}

	//Método HTTP POST /user/new para solicitar el registro  
	//	Data OUT: {HTTP 201 "created" / HTTP 422} el usuario no
	//	tiene que estar autentificado ya que se va a crear uno nuevo (registro)
	public function postUser($data) {
		$user = new User($data->alias, $data->passwd, $data->email);
		try {
			$user->checkIsValidForRegister();

			$this->userMapper->save($user);

			//DATA_OUT 201 Created
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			//Cambiamos la URI a /account donde account será el alias
			header("Location: ".$_SERVER['REQUEST_URI']."/".$data->alias);
		}catch(ValidationException $e) {
			//422 Unprocessable entity. Solicitud no puede ser procesada debido a la lógica de negocio o reglas de validación. 
			http_response_code(422); 
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	//Método HTTP GET /user/checkAvailability/<alias>. Se usará para saber
	//	si hay un usuario registrado con ese alias.
	//	Data OUT: {HTTP 200 OK "si el usuario existe" / HTTP 204 "no content" si no existe ningún nombre de usuario con ese nombre}
	// usaremos la función aliasExists() de UserMapper.php
	public function getUser($alias){
		if($this->userMapper->aliasExists($alias)){
			//El usuario existe
			header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
			echo("\nEl usuario existe");
		}else{
			//No se encontró el usuario
			echo("\nEl usuario no existe en la base de datos.");
			header($_SERVER['SERVER_PROTOCOL'] . ' 204 No Content');
		}
	}

	//Nueva funcionalidad: reestablecer contraseña si se ha olvidado por el usuario

	//COMPROBADA FUNCIONA CORRECTAMENTE
	//Método POST /user/delete. Se usa para la eliminación de la cuenta insertando
	//	alias y passwd.
	//	Data OUT: {HTTP 200 OK / HTTP 400 / HTTP 404 Not Found}
	public function deleteUserAccount($data){

		//El usuario debe estar registrado
		if ($this->userMapper->isValidUser($data->alias, $data->passwd)) {	
			//revisar deleteUser(), elimina pero no sale de la función. 
			if($this->userMapper->deleteUser($data->alias, $data->passwd)){
				// Datos de salida HTTP 200 OK
				//Usuario eliminado correctamente.
				header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
				echo("\nUsuario eliminado correctamente.");
			}else{
				header($_SERVER['SERVER_PROTOCOL'] . ' 400');
				echo("\nNo se ha podido eliminar dicho usuario.");
			}
			
		}else{
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
			echo("\nNo se encuentra el usuario en la base de datos, ¿ya lo has eliminado?");
			$errors = array();
			$errors["general"] = "Alias o contraseña no valido/s";

		}
	}


	//COMPROBADO Y CORRECTO

	//Método POST /user/edit. Se usa para la edición de los datos de la cuenta, 
	//	el usuario debe insertar alias y passwd antigua correctamente ($data), además de 
	//  alias, passwd, email si los quiere actualizar.
	// 	no puede actualziar la passwd, para ello existe otra función. 
	//	Data OUT: {HTTP 201 OK / HTTP 400}
	public function editUserAccount($dataOld, $dataNew){

		//si el usuario está dentro de la base de datos. 
		if ($this->userMapper->isValidUser($dataOld->aliasOld, $dataOld->passwdOld)) {

				// Creamos un nuevo usuario con las variables actualizadas
				// y lo almacenamos en la base de datos. 
				//$user = new User($alias, $passwd, $email);
				try {
					$user->checkIsValidForRegister();
					if(!empty($dataNew->aliasNew) || !empty($dataNew->emailNew)){

						//Eliminamos el usuario con las variables desactualizadas. 
						$this->userMapper->deleteUser($dataOld->aliasOld, $dataOld->passwdOld);

						if(!empty($dataNew->aliasNew) && empty($dataNew->emailNew)){
							//creamos un nuevo usuario con el nuevo alias y el mismo email
							$user = new User($dataNew->aliasNew, $dataOld->passwdOld, $dataOld->emailOld);
							$user->checkIsValidForRegister();

							$this->userMapper->save($user);
						}
						if(empty($dataNew->aliasNew) && !empty($dataNew->emailNew)){
							//Creamos un nuevo usuario con el nuevo email y el mismo alias
							$user = new User($dataOld->aliasOld, $dataOld->passwdOld, $dataNew->emailNew);
							$user->checkIsValidForRegister();

							$this->userMapper->save($user);
						}
						if(!empty($dataNew->aliasNew) && !empty($dataNew->emailNew)){
							//Creamos un nuevo usuario con nuevo alias y nuevo email
							$user = new User($dataNew->aliasNew, $dataOld->passwdOld, $dataNew->emailNew);
							$user->checkIsValidForRegister();

							$this->userMapper->save($user);
						}

						header($_SERVER['SERVER_PROTOCOL'].' 201 Edited');
						header("Location: ".$_SERVER['REQUEST_URI']."/".$data->alias);
					}

					else{
						header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
						echo("\nDebe indicar los nuevos valores a editar.");
					}
		
				}catch(ValidationException $e) {
					//No se ha podido guardar el usuario en la base de datos.
					http_response_code(400); 
					header('Content-Type: application/json');
					echo(json_encode($e->getErrors()));
				}
				

		}else{
			$errors = array();
			$errors["general"] = "Alias o contraseña no valido/s.";
			$this->view->setVariable("errors", $errors);
			http_response_code(400);
		}
	}


	

}

// URI-MAPPING for this Rest endpoint
$userRest = new UserRest();
URIDispatcher::getInstance()
->map("GET",	"/user/$1", array($userRest,"login"))
->map("POST", "/user", array($userRest,"postUser"))
->map("GET", "/user/checkAvailability/$1", array($userRest,"getUser"))
->map("POST", "/user/delete", array($userRest,"deleteUserAccount"))
->map("POST", "/user/edit", array($userRest,"editUserAccount"));
