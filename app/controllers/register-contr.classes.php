<?php

class RegisterContr extends Register {

	private $uid;
	private $pwd;
	private $pwdRepeat;
	private $email;
	private $notify;
	private $agree;
	private $profanity;


	public function __construct($uid, $pwd, $pwdRepeat, $email, $notify, $agree, $profanity) {
		$this->uid = $uid;
		$this->pwd = $pwd;
		$this->pwdRepeat = $pwdRepeat;
		$this->email = $email;
		$this->notify = $notify;
		$this->agree = $agree;
		$this->profanity = $profanity;
	}

	public function registerUser() {
		if($this->emptyInput() == false) {
			$_SESSION['error'] = 'Error! Please fill out the entire form';
			header('location: ../index.php?register');
			exit();
		}
		if($this->invalidUid() == false) {
			$_SESSION['error'] = 'Error! Username may only contain letters and numbers';
			header('location: ../index.php?register');
			exit();
		}
		if($this->uidLength() == false) {
			$_SESSION['error'] = 'Error! Username must be atleast 3 characters';
			header('location: ../index.php?register');
			exit();
		}
		if($this->uidProfanity() == false) {
			$_SESSION['error'] = 'Error! Username contains profanity, please choose a different username';
			header('location: ../index.php?register');
			exit();
		}
		if($this->invalidEmail() == false) {
			$_SESSION['error'] = 'Error! You must enter a valid email';
			header('location: ../index.php?register');
			exit();
		}
		if($this->pwdLength() == false) {
			$_SESSION['error'] = 'Error! Password must be a minimum of 8 characters';
			header('location: ../index.php?register');
			exit();
		}
		if($this->pwdUpperLower() == false) {
			$_SESSION['error'] = 'Error! Password must contain atleast one uppercase and lowercase letter';
			header('location: ../index.php?register');
			exit();
		}
		if($this->pwdNumber() == false) {
			$_SESSION['error'] = 'Error! Password must contain atleast one number';
			header('location: ../index.php?register');
			exit();
		}
		if($this->pwdLettersNumbers() == false) {
			$_SESSION['error'] = 'Error! Password may only contain letters and numbers';
			header('location: ../index.php?register');
			exit();
		}
		if($this->pwdMatch() == false) {
			$_SESSION['error'] = 'Error! Your passwords do not match';
			header('location: ../index.php?register');
			exit();
		}
		if($this->uidExists() == false) {
			$_SESSION['error'] = 'Error! Chosen username or email already exists';
			header('location: ../index.php?register');
			exit();
		}
		if($this->agreement() == false) {
			$_SESSION['error'] = 'Error! You need to agree to the terms and conditions to register an account';
			header('location: ../index.php?register');
			exit();
		}
		$this->setUser($this->uid, $this->pwd, $this->email, $this->notify);
	}

	// Check if form is empty
	private function emptyInput() {
		$result;
		if(empty($this->uid) || empty($this->pwd) || empty($this->pwdRepeat) || empty($this->email))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if uid contains only letters and numbers
	private function invalidUid() {
		$result;
		if(!ctype_alnum($this->uid))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if username is > 3 characters
	private function uidLength() {
		$result;
		if(strlen($this->uid) < 3)
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if uid contains profanity
	private function uidProfanity() {
		$result;
		if(in_array($this->uid,$this->profanity))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if email is valid
	private function invalidEmail() {
		$result;
		if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if pwd is < 8 characters
	private function pwdLength() {
		$result;
		if(strlen($this->pwd) < 8)
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if pwd contains a lowercase and an uppercase character
	private function pwdUpperLower() {
		$result;
		if(preg_match("/[A-Z]/", $this->pwd) === 0 || preg_match("/[a-z]/", $this->pwd) === 0)
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if pwd contains a number
	private function pwdNumber() {
		$result;
		if(preg_match("/[0-9]/", $this->pwd) === 0)
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if pwd contains only numbers & letters
	private function pwdLettersNumbers() {
		$result;
		if((!ctype_alnum($this->pwd)) || (!ctype_alnum($this->pwdRepeat)))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if passwords match
	private function pwdMatch() {
		$result;
		if($this->pwd !== $this->pwdRepeat)
		{
			$result = false;
		} 
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if uid exists is already taken
	private function uidExists() {
		$result;
		if(!$this->checkUser($this->uid, $this->email))
		{
			$result = false;
		} 
		else
		{
			$result = true;
		}
		return $result;
	}
	
	// Check if user agreed to terms & conditions
	private function agreement() {
		$result;
		if($this->agree)
		{
			$result = true;
		} 
		else
		{
			$result = false;
		}
		return $result;
	}
	
}