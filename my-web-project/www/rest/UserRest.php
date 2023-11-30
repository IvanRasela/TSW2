<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");
require_once(__DIR__."/BaseRest.php");

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
	}

	//Método HTTP POST /account para el inicio de sesión
	//	Data IN: {"alias", "passwd"}
	//	Data OUT: {HTTP 200-OK, set cookie "bearer_token" / HTTP 403 Forbidden}
	public function login($alias) {
		$currentLogged = parent::authenticateUser();
		if ($currentLogged->getAlias() != $alias) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden'); //el servidor entiende la solicitud pero se niega a cumplirla
			echo("You are not authorized to login as anyone but you");
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok'); 
			echo("Hello ".$alias);
		}
	}

	//Método HTTP POST /account/new para solicitar el registro  
	//	para ello se le pedirá el nombre de usuario, contraseña y email.
	//	Data OUT: {HTTP 201 "created" / HTTP 400} el usuario no
	//	tiene que estar autentificado ya que se va a crear uno nuevo (registro)
	public function postUser($data) {
		$user = new User($data->alias, $data->passwd, $data->email);
		try {
			$user->checkIsValidForRegister();

			$this->userMapper->save($user);

			//DATA_OUT 200 OK
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			//Cambiamos la URI a /account donde account será el alias
			header("Location: ".$_SERVER['REQUEST_URI']."/".$data->alias);
		}catch(ValidationException $e) {
			http_response_code(400); //la solicitud no pudo ser entendida o procesada por el servidor
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	//Método HTTP GET /account/userAvailability/<alias>. Se usará para saber
	//	si hay un usuario registrado con ese alias.
	//	Data OUT: {HTTP 200 OK "si el usuario existe" / HTTP 204 404 "no content" si no existe ningún nombre de usuario con ese nombre}
	// usaremos la función aliasExists() de UserMapper.php
	public function getUser($alias){
		if($this->userMapper->aliasExists($alias)){
			//El usuario existe
			header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
			echo("El usuario existe");
		}else{
			//No se encontró el usuario
			header($_SERVER['SERVER_PROTOCOL'] . ' 204 No Content');
			echo("No existe ningún nombre de usuario con ese nombre");
		}
	}

	//Nueva funcionalidad: reestablecer contraseña y se ha olvidado el usuairo


	//Método PUT /account. Se usa para la edición de los datos de la cuenta, 
	//	el usuario debe estar registrado, podrá cambiar su alias, su contraseña (si 
	//	inserta bien la antigua) y el email
	//	Data OUT: {HTTP 201 OK / HTTP 400}
	public function editUserAccount($alias, $passwd, $newPasswd, $email){

		//El usuario debe estar registrado
		$user = $this->userMapper->findByAlias($alias);

		if ($user !== null && $this->userMapper->isValidUser($alias, $passwd)) {
				if($alias!=NULL){
					$user->setAlias($alias);
				}
				if($email!=NULL){
					$user->setEmail($email);
				}
				if($user->getPasswd()==$newPasswd && $newPasswd!=NULL){
					$user->setPassword($newPasswd);
				}
				header($_SERVER['SERVER_PROTOCOL'].' 201 Edited');
				header("Location: ".$_SERVER['REQUEST_URI']."/".$data->alias);

		}else{
			$errors = array();
			$errors["general"] = "Alias o contraseña no valido/s";
			$this->view->setVariable("errors", $errors);
			http_response_code(400);
		}
	}


	//Método DELETE /account. Se usa para la eliminación de la cuenta insertando
	//	alias y passwd.
	//	Data OUT: {HTTP 200 OK / HTTP 400}
	public function deleteUserAccount($alias, $passwd){
		//El usuario debe estar registrado
		$user = $this->userMapper->findByAlias($alias);

		if ($user !== null && $this->userMapper->isValidUser($alias, $passwd)) {
				
			//Nueva función del UserMapper
			if($this->userMapper->deleteByAlias($alias)){
				// Datos de salida HTTP 200 OK
				header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
			}else{
				header($_SERVER['SERVER_PROTOCOL'] . ' 400');
				echo("No se ha podido eliminar dicho usuario");
			}
			
		}else{
			http_response_code(400);
			$errors = array();
			$errors["general"] = "Alias o contraseña no valido/s";
			$this->view->setVariable("errors", $errors);
		}
	}

}

// URI-MAPPING for this Rest endpoint
$userRest = new UserRest();
URIDispatcher::getInstance()
->map("GET",	"/user/$1", array($userRest,"login"))
->map("POST", "/user", array($userRest,"postUser"));
