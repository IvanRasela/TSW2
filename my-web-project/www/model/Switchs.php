
<?php
// file: model/Switch.php

require_once(__DIR__."/../core/ValidationException.php");

/**
* Class Switch
*
* Represents a Switch in the blog. A Switch was written by an
* specific User (author) and contains a list of Comments
*
* @author lipido <lipido@gmail.com>
*/
class Switchs {

	/**
	* The name of this switchs
	* @var string
	*/
	private $SwitchName;

	/**
	* The private uuid
	* @var string
	*/
	private $Private_UUID;

	/**
	* The public uuid
	* @var string
	*/
	private $Public_UUID;

	/**
	* The author of this switchs
	* @var User
	*/
	private $AliasUser;

	/**
	* The description of this switchs
	* @var string
	*/
	private $DescriptionSwitch;//DescriptionSwitch


	/**
	* The last time power on
	* @var varchar
	*/
	private $LastTimePowerOn;

	/**
	* The max time power on
	* @var varchar
	*/
	private $MaxTimePowerOn;

	/**
	* The constructor
	*
	* @param string $SwitchName The name of the switchs
	* @param string $Private_UUID The private uuid
	* @param string $Public_UUID The public uuid
	* @param User $AliasUser The alias of user
	* @param string $DescriptionSwitch The description of this switchs
	* @param string $LastTimePowerOn The last time power on
	* @param string $MaxTimePowerOn The max time power on
	*/
	public function __construct($SwitchName=NULL, $Private_UUID=NULL, $Public_UUID=NULL, User $AliasUser=NULL, $DescriptionSwitch=NULL,$LastTimePowerOn=NULL,$MaxTimePowerOn=NULL) {
		$this->SwitchName = $SwitchName;
		$this->Private_UUID = $Private_UUID;
		$this->Public_UUID = $Public_UUID;
		$this->AliasUser = $AliasUser;
		$this->DescriptionSwitch = $DescriptionSwitch;
		$this->LastTimePowerOn = $LastTimePowerOn;
		$this->MaxTimePowerOn = $MaxTimePowerOn;

	}

	/**
	* Gets the id of this switch
	*
	* @return string The id of this switch
	*/
	public function getSwitchName() {
		return $this->SwitchName;
	}

	public function setSwitchName($SwitchName) {
		$this->SwitchName = $SwitchName;
	}

	/**
	* Gets the title of this switch
	*
	* @return string The title of this switch
	*/
	public function getPrivate_UUID() {
		return $this->Private_UUID;
	}

	/**
	* Gets the title of this switch
	*
	* @return string The title of this switch
	*/
	public function getPublic_UUID() {
		return $this->Public_UUID;
	}

	/**
	* Sets the title of this switch
	*
	* @param string $title the title of this switch
	* @return void
	*/
	public function setPrivate_UUID($Private_UUID) {
		$this->Private_UUID = $Private_UUID;
	}

	/**
	* Sets the title of this switch
	*
	* @param string $title the title of this switch
	* @return void
	*/
	public function setPublic_UUID($Public_UUID) {
		$this->Public_UUID = $Public_UUID;
	}

	/**
	* Gets the content of this switch
	*
	* @return User The content of this switch
	*/
	public function getAliasUser() {
		return $this->AliasUser;
	}

	/**
	* Sets the content of this switch
	*
	* @param string $content the content of this switch
	* @return void
	*/
	public function setAliasUser(User $AliasUser) {
		$this->AliasUser = $AliasUser;
	}

	/**
	* Gets the author of this switch
	*
	* @return string The author of this switch
	*/
	public function getDescriptionSwitch() {
		return $this->DescriptionSwitch;
	}

	/**
	* Sets the author of this switch
	*
	* @param string $author the author of this switch
	* @return void
	*/
	public function setDescriptionSwitch($DescriptionSwitch) {
		$this->DescriptionSwitch = $DescriptionSwitch;
	}


	/**
	* Gets the list of comments of this switch
	*
	* @return time The list of comments of this switch
	*/
	public function getLastTimePowerOn() {
		return $this->LastTimePowerOn;
	}

	/**
	* Sets the comments of the switch
	*
	* @param time $comments the comments list of this switch
	* @return void
	*/
	public function setLastTimePowerOn($LastTimePowerOn) {
		$this->LastTimePowerOn = $LastTimePowerOn;
	}

	/**
	* Gets the list of comments of this switch
	*
	* @return time The list of comments of this switch
	*/
	public function getMaxTimePowerOn() {
		return $this->MaxTimePowerOn;
	}

	/**
	* Sets the comments of the switch
	*
	* @param time $comments the comments list of this switch
	* @return void
	*/
	public function setMaxTimePowerOn($MaxTimePowerOn) {
		$this->MaxTimePowerOn = $MaxTimePowerOn;
	}

	/**
	* Checks if the current instance is valid
	* for being updated in the database.
	*
	* @throws ValidationException if the instance is
	* not valid
	*
	* @return void
	*/
	public function checkIsValidForCreate() {
		$errors = array();
		if (strlen(trim($this->SwitchName)) == 0 ) {
			$errors["SwitchName"] = "SwitchName is mandatory";
		}

		if (sizeof($errors) > 0){
			throw new ValidationException($errors, "switch is not valid");
		}
	}

	/**
	* Checks if the current instance is valid
	* for being updated in the database.
	*
	* @throws ValidationException if the instance is
	* not valid
	*
	* @return void
	*/
	public function checkIsValidForUpdate() {
		$errors = array();

		if (!isset($this->id)) {
			$errors["id"] = "id is mandatory";
		}

		try{
			$this->checkIsValidForCreate();
		}catch(ValidationException $ex) {
			foreach ($ex->getErrors() as $key=>$error) {
				$errors[$key] = $error;
			}
		}
		if (sizeof($errors) > 0) {
			throw new ValidationException($errors, "switch is not valid");
		}
	}
}