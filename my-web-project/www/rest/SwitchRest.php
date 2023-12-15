
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

	public function getSwitchs() {
		$currentUser = parent::authenticateUser();
		$switchs = $this->SwitchsMapper->findAll($currentUser);

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

	}
	public function getSwitchsByPublic($uuid) {
		$switch = $this->SwitchsMapper->findByPublicUUID($uuid);
		
		if($switch == NULL){
			header($_SERVER['SERVER_PROTOCOL'].' 404 Not found');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
		header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		header('Content-Type: application/json');
		echo(json_encode($switch));
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

	}

	public function createSwitch($data) {
		$currentUser = parent::authenticateUser();
		$switch = new Switchs();



		if (isset($data->title) && isset($data->content)) {
			$switch->setTitle($data->title);
			$switch->setContent($data->content);

			$switch->setAuthor($currentUser);
		}

		try {
			// validate Post object
			$switch->checkIsValidForCreate(); // if it fails, ValidationException

			// save the Post object into the database
			$switchId = $this->SwitchsMapper->save($switch);

			// response OK. Also send post in content
			header($_SERVER['SERVER_PROTOCOL'].' 201 Created');
			header('Location: '.$_SERVER['REQUEST_URI']."/".$switchId);
			header('Content-Type: application/json');
			echo(json_encode(array(
				"id"=>$switchId,
				"title"=>$switch->getTitle(),
				"content" => $switch->getContent()
			)));

		} catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}


	public function updatePost($postId, $data) {
		$currentUser = parent::authenticateUser();

		$post = $this->postMapper->findById($postId);
		if ($post == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Post with id ".$postId." not found");
			return;
		}

		// Check if the Post author is the currentUser (in Session)
		if ($post->getAuthor() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the author of this post");
			return;
		}
		$post->setTitle($data->title);
		$post->setContent($data->content);

		try {
			// validate Post object
			$post->checkIsValidForUpdate(); // if it fails, ValidationException
			$this->postMapper->update($post);
			header($_SERVER['SERVER_PROTOCOL'].' 200 Ok');
		}catch (ValidationException $e) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			header('Content-Type: application/json');
			echo(json_encode($e->getErrors()));
		}
	}

	public function deleteSwitch($switchuuid) {
		$currentUser = parent::authenticateUser();
		$switch = $this->SwitchsMapper->findById($switchuuid);

		if ($switch == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Post with id ".$postId." not found");
			return;
		}
		// Check if the Post author is the currentUser (in Session)
		if ($switch->getAliasUser->getAlias() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 401 Unauthorized');
			echo("you are not the author of this post");
			return;
		}

		$this->SwitchsMapper->delete($switch);

		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

	public function turnOnOffSwitchPublic($uuid) {
		$currentUser = parent::authenticateUser();
		$switch = $this->SwitchsMapper->findById($switchuuid);

		if ($switch == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Post with id ".$postId." not found");
			return;
		}
		// Check if the Post author is the currentUser (in Session)
		if ($switch->getAliasUser->getAlias() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the author of this post");
			return;
		}

		$this->SwitchsMapper->changeState($switch);

		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

	public function turnOnOffSwitchPrivate($uuid) {
		$switch = $this->SwitchsMapper->findById($switchuuid);

		if ($switch == NULL) {
			header($_SERVER['SERVER_PROTOCOL'].' 400 Bad request');
			echo("Post with id ".$postId." not found");
			return;
		}
		// Check if the Post author is the currentUser (in Session)
		if ($switch->getAliasUser->getAlias() != $currentUser) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo("you are not the author of this post");
			return;
		}

		$this->SwitchsMapper->changeState($switch);

		header($_SERVER['SERVER_PROTOCOL'].' 204 No Content');
	}

	

}

// URI-MAPPING for this Rest endpoint
$switchRest = new SwitchRest();
URIDispatcher::getInstance()
->map("GET",	"/post", array($switchRest,"getSwitchs"))
->map("GET",	"/post", array($switchRest,"getSwitchsByPublic"))
->map("GET",	"/post", array($switchRest,"getSwitchsByPrivate"))
->map("GET",	"/post", array($switchRest,"getSwitchsSuscribe"))
->map("POST", "/post", array($switchRest,"createSwitch"))
->map("PUT",	"/post/$1", array($switchRest,"updateSwitch"))
->map("DELETE", "/post/$1", array($switchRest,"deleteSwitch"));