<?php
// file: model/User.php

require_once(__DIR__."/../core/ValidationException.php");

/**
* Class User
*
* Represents a User in the blog
*
* @author lipido <lipido@gmail.com>
*/
class User {

	/**
	* The user name of the user
	* @var string
	*/
	private $alias;

	/**
	* The password of the user
	* @var string
	*/
	private $passwd;


	/**
	* The email of the user
	* @var string
	*/
	private $email;

	/**
	* The constructor
	*
	* @param string $username The name of the user
	* @param string $passwd The password of the user
	*/
	public function __construct($alias=NULL, $passwd=NULL, $email=NULL) {
		$this->alias = $alias;
		$this->passwd = $passwd;
		$this->email = $email;
	}

	/**
	* Gets the username of this user
	*
	* @return string The username of this user
	*/
	public function getAlias() {
		return $this->alias;
	}

	/**
	* Sets the username of this user
	*
	* @param string $username The username of this user
	* @return void
	*/
	public function setAlias($alias) {
		$this->alias = $alias;
	}

	/**
	* Gets the password of this user
	*
	* @return string The password of this user
	*/
	public function getPasswd() {
		return $this->passwd;
	}
	/**
	* Sets the password of this user
	*
	* @param string $passwd The password of this user
	* @return void
	*/
	public function setPassword($passwd) {
		$this->passwd = $passwd;
	}

	/**
	* Gets the email of this user
	*
	* @return string The password of this user
	*/
	public function getEmail() {
		return $this->email;
	}
	/**
	* Sets the password of this user
	*
	* @param string $passwd The password of this user
	* @return void
	*/
	public function setEmail($email) {
		$this->email = $email;
	}

	/**
	* Checks if the current user instance is valid
	* for being registered in the database
	*
	* @throws ValidationException if the instance is
	* not valid
	*
	* @return void
	*/
	public function checkIsValidForRegister() {
		$errors = array();
		if (strlen($this->alias) < 5) {
			$errors["alias"] = "El alias no puede ser menor a 5 carácteres.";

		}
		if (strlen($this->passwd) < 5) {
			$errors["passwd"] = "La contraseña no puede ser menor a 5 carácteres.";
		}
		//El email puede estar vacio, pero si no lo está debe validarse
		 if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
        	// Validar formato
       		$errors["email"] = "El formato del correo electrónico no es válido.";
   		}
		
		if (sizeof($errors)>0){
			throw new ValidationException($errors, "Usuario no valido.");
		}
	}
}