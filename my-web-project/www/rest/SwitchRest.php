<?php

require_once(__DIR__."/../model/User.php");
require_once(__DIR__."/../model/UserMapper.php");

require_once(__DIR__."/../model/Switchs.php");
require_once(__DIR__."/../model/SwitchsMapper.php");

require_once(__DIR__."/BaseRest.php");

require_once(__DIR__."/URIDispatcher.php");

class SwitchRest extends BaseRest {
	private $SwitchsMapper;

	public function __construct() {
		parent::__construct();
		
		
		$this->SwitchsMapper = new SwitchsMapper();
	}

	//función de prueba, si funciona
	public function findAllSwitches(){
		$switchs = $this->SwitchsMapper->findAll();
		if($switchs==NULL){
			header($_SERVER['SERVER_PROTOCOL'].' 401 Not found');
		}else{
			echo(json_encode($switchs));
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		}

	}

	public function getSwitches() {

		$switchs = $this->SwitchsMapper->findAll();
		if($switchs==NULL){
			header($_SERVER['SERVER_PROTOCOL'].' 400 NotFound');
		}else{
			$switchs_array = array();
			
			foreach ($switchs as $switch) {
				array_push($switchs_array, array(
					"SwitchName" => $switch->getSwitchName(),
					"Public_UUID" => $switch->getPublic_UUID(),
					"Private_UUID" =>$switch->getPrivate_UUID(),
					"AliasUser" =>$switch->getAliasUser()->getAlias()
				));
			}
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
			header('Content-Type: application/json');
			echo(json_encode($switchs_array));
		}
		
	}


	public function getSwitchsByPublic($uuid) {
		try{
			$switch = $this->SwitchsMapper->findById($uuid);
		
			if($switch == NULL){
				header($_SERVER['SERVER_PROTOCOL'].' 204 Not found');
				header('Content-Type: application/json');
				echo(json_encode($e->getErrors()));
			}else{
				header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
				header('Content-Type: application/json');
				echo(json_encode($switch));
			}
		}catch(ValidationException $e) {
			http_response_code(400); 
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
		
	}


	public function getSwitchsByPrivate($uuid) {
		$switchs = $this->SwitchsMapper->findByPrivateUUID($uuid);

		if($switch == NULL){
			header($_SERVER['SERVER_PROTOCOL'].' 404 Not found');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($switch));
	}

	public function getSwitchsSuscribe() {
		try{

			$currentUser = parent::authenticateUser();
			$switchs = $this->SwitchsMapper->findIfSuscribe($currentUser);

			$switchs_array = array();
			foreach($switchs as $switch) {
				array_push($switchs_array, array(
					"SwitchName" => $switch->getSwitchName(),
					"Public_UUID" => $switch->getPublic_UUID(),
				));
			}

			if($switchs_array == NULL){
				header($_SERVER['SERVER_PROTOCOL'].' 404 Not found');
				header('Content-Type: application/json');
				echo(json_encode($e->getErrors()));
			}

			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
			header('Content-Type: application/json');
			echo(json_encode($switchs_array));


		}catch (ValidationException $e) {
			http_response_code(400);
			header('Content-Type: application/json');
			error_log("ValidationException: " . json_encode($e->getErrors()));
			echo(json_encode($e->getErrors()));
		}
	}

	public function createSwitch($data) {
		try {
			$currentUser = parent::authenticateUser();
			$switch = new Switchs();

			if (isset($data->title) && isset($data->content)) {
				$switch->setTitle($data->title);
				$switch->setDescriptionSwitch($data->content);

				$switch->setAliasUser($currentUser);
			}
			$switch->checkIsValidForCreate(); // if it fails, ValidationException

			$switchId = $this->SwitchsMapper->save($switch);

			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$switchId);
			header('Content-Type: application/json');
			echo(json_encode(array(
				"id"=>$switchId,
				"aliasuser"=>$switch->getAliasUser(),
				"description" => $switch->getDescriptionSwitch()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}


	public function deleteSwitch($switchuuid) {
		try{
			$currentUser = parent::authenticateUser();
			$switch = $this->SwitchsMapper->findById($switchuuid);

			if ($switch == NULL) {
				header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
				echo("Switch with id ".$switchId." not found");
				return;
			}
			// Check if the Switch author is the currentUser (in Session)
			if ($switch->getAliasUser->getAlias() != $currentUser) {
				header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
				echo("you are not the author of this switch");
				return;
			}

		$this->SwitchsMapper->delete($switch);

		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
			
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
		
	}

	public function turnOnOffSwitchPublic($uuid) {
		try{
			$currentUser = parent::authenticateUser();
			$switch = $this->SwitchsMapper->findById($switchuuid);

			if ($switch == NULL) {
				header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
				echo("Switch with id ".$switchId." not found");
				return;
			}
			// Check if the Switch author is the currentUser (in Session)
			if ($switch->getAliasUser->getAlias() != $currentUser) {
				header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
				echo("you are not the author of this switch");
				return;
			}

			$this->SwitchsMapper->changeState($switch);

			header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
		
	}

	public function turnOnOffSwitchPrivate($uuid) {
		try{
			$currentUser = parent::authenticateUser();
			$switch = $this->SwitchsMapper->findById($switchuuid);

			if ($switch == NULL) {
				header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
				echo("Switch with id ".$switchId." not found");
				return;
			}
			if ($switch->getAliasUser->getAlias() != $currentUser) {
				header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
				echo("you are not the author of this switch");
				return;
			}

			$this->SwitchsMapper->changeState($switch);

			header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
		
	}
}

// URI-MAPPING for this Rest endpoint
$switchRest = new SwitchRest();
URIDispatcher::getInstance()
->map("GET",	"/switch", array($switchRest,"getSwitches"))
->map("GET",	"/switch/public/$1", array($switchRest,"getSwitchsByPublic"))
->map("GET",	"/switch/private/$1", array($switchRest,"getSwitchsByPrivate"))
->map("GET",	"/switch/suscribers", array($switchRest,"getSwitchsSuscribe"))//revisar pq en switchservice se le pasa la uuid
->map("POST", "/switch/new/$1", array($switchRest,"createSwitch"))//revisar en el service
->map("DELETE", "/switch/del/$1", array($switchRest,"deleteSwitch"))
->map("GET", "/switch/all", array($switchRest,"findAllSwitches"));//quitar esta que es para comprobar solo
