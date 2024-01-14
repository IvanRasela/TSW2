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

	public function getSwitches($user) {

		$switchs = $this->SwitchsMapper->findAll($user);
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
	


	
	public function getSuscribers($user) {

		$switchs = $this->SwitchsMapper->findIfSuscribe($user);
		if($switchs==NULL){
			header($_SERVER['SERVER_PROTOCOL'].' 400 NotFound');
		}else{
			$switchs_array = array();
			
			foreach ($switchs as $switch) {
				array_push($switchs_array, array(
					"SwitchName" => $switch->getSwitchName(),
					"Private_UUID" => $switch->getPrivate_UUID(),
					"Public_UUID" => $switch->getPublic_UUID(),
					"AliasUser" =>$switch->getAliasUser()->getAlias(),
					"DescriptionSwitch" =>$switch->getDescriptionSwitch(),
					"LastTimePowerOn" =>$switch->getLastTimePowerOn(),
					"MaxTimePowerOn" =>$switch->getMaxTimePowerOn()
				));
			}
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
			header('Content-Type: application/json');
			echo(json_encode($switchs_array));
			
		}
		
	}
	public function getSwitchsByUUID($uuid) {
		$switch = $this->SwitchsMapper->findById($uuid);
		
		if ($switch == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 204 Not Found');
		} else {
			$switch_data = array(
				"SwitchName" => $switch->getSwitchName(),
				"Public_UUID" => $switch->getPublic_UUID(),
				"AliasUser" => $switch->getAliasUser()->getAlias()
			);

			header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
			header('Content-Type: application/json');
			echo(json_encode($switch_data));
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

	public function getSwitchsSuscribe($user) {
		$switchs = $this->SwitchsMapper->findIfSuscribe($user);
		if($switchs==NULL){
			header($_SERVER['SERVER_PROTOCOL'].' 400 NotFound');
		}else{
			$switchs_array = array();
			
			foreach ($switchs as $switch) {
				array_push($switchs_array, array(
					"SwitchName" => $switch->getSwitchName(),
					"Public_UUID" => $switch->getPublic_UUID(),
				));
			}
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
			header('Content-Type: application/json');
			echo(json_encode($switchs_array));
		}
	}

	public function createSwitch($data) {

		$currentUser = parent::authenticateUser();
		$switch = new Switchs();

		if (isset($data->SwitchName)) {
			$switch->setAliasUser($currentUser);
			$switch->setDescriptionSwitch($data->DescriptionSwitch);
			$switch->setSwitchName($data->SwitchName);
			//Hay que pasar a tipo timestamp al guardar en la base de datos
			$switch->setMaxTimePowerOn($data->MaxTimePowerOn);
			
			//Se generan las claves al guardar en la base de datos. 
		}

		try {
			
			$switch->checkIsValidForCreate(); 

			$this->SwitchsMapper->save($switch);

			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			//
			header('Content-Type: application/json');
			echo(json_encode(array(
				"SwitchName" => $switch->getSwitchName(),
				"Public_UUID" => $switch->getPublic_UUID(),
				"AliasUser" => $switch->getAliasUser()->getAlias(),
				"DescriptionSwitch" =>$switch->getDescriptionSwitch(),
				"MaxTimePowerOn" =>$switch->getMaxTimePowerOn(),
				"LastTimePowerOn" =>$switch->getLastTimePowerOn(),
				"Private_UUID" =>$switch->getPrivate_UUID(),
				"Public_UUID" =>$switch->getPublic_UUID()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function createPost($data) {
		$currentUser = parent::authenticateUser();
		$post = new Post();

		if (isset($data->title) && isset($data->content)) {
			$post->setTitle($data->title);
			$post->setContent($data->content);

			$post->setAuthor($currentUser);
		}

		try {
			// validate Post object
			$post->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Post object into the database
			$postId = $this->postMapper->save($post);

			// response OK. Also send post in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$postId);
			header('Content-Type: application/json');
			echo(json_encode(array(
				"id"=>$postId,
				"title"=>$post->getTitle(),
				"content" => $post->getContent()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}


	public function deleteSwitch($uuid) {
		try{
			//$currentUser = parent::authenticateUser();
			$switch = $this->SwitchsMapper->findById($uuid);

			$this->SwitchsMapper->delete($switch);

			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
			
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
->map("GET",	"/switch/$1", array($switchRest,"getSwitches"))
->map("GET",	"/switch/suscribers/$1", array($switchRest,"getSuscribers"))
->map("GET",	"/switch/public/$1", array($switchRest,"getSwitchsByUUID"))
->map("GET",	"/switch/private/$1", array($switchRest,"getSwitchsByPrivate"))//No se usa
->map("GET",	"/switch/suscribe/$1", array($switchRest,"getSwitchsSuscribe"))//revisar pq en switchservice se le pasa la uuid
->map("POST", "/switch/new", array($switchRest,"createSwitch"))//revisar en el service
->map("DELETE", "/switch/del/$1", array($switchRest,"deleteSwitch"))
->map("GET", "/switch/all", array($switchRest,"findAllSwitches"));//quitar esta que es para comprobar solo
