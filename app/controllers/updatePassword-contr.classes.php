<?php
class UpdatePasswordContr extends UpdatePassword {

	private $uid;
    private $pwd;
    private $repeatPwd;
	private $oldPwd;

	public function __construct($uid, $pwd, $repeatPwd, $oldPwd) {
		$this->uid = $uid;
        $this->pwd = $pwd;
        $this->repeatPwd = $repeatPwd;
		$this->oldPwd = $oldPwd;
	}

	public function updatePasswordUser() {
		if($this->emptyUid() == false) {
			$_SESSION['error'] = 'Error! You need to log in again';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->emptyInput() == false) {
			$_SESSION['error'] = 'Please fill in the entire form';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->pwdLength() == false) {
			$_SESSION['error'] = 'Error! Password must be a minimum of 8 characters';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->pwdUpperLower() == false) {
			$_SESSION['error'] = 'Error! Password must contain atleast one uppercase and lowercase letter';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->pwdNumber() == false) {
			$_SESSION['error'] = 'Error! Password must contain atleast one number';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->pwdLettersNumbers() == false) {
			$_SESSION['error'] = 'Error! Password may only contain letters and numbers';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->pwdMatch() == false) {
			$_SESSION['error'] = 'Error! Your passwords do not match';
			header('location: ../dashboard.php');
			exit();
		}
		// check if user exists
		if($this->uidExists() == false) {
			$_SESSION['error'] = 'Error! Chosen username or email already exists';
			header('location: ../dashboard.php');
			exit();
		}

		$this->updatePassword($this->uid, $this->pwd);
	}

	// check if uid isn't set
	private function emptyUid() {
		$result;
		if(empty($this->uid))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if passwords are empty
	private function emptyInput() {
		$result;
		if(empty($this->pwd) || empty($this->repeatPwd) || empty($this->oldPwd))
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
		if((!ctype_alnum($this->pwd)) || (!ctype_alnum($this->repeatPwd)))
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
		if($this->pwd !== $this->repeatPwd)
		{
			$result = false;
		} 
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if uid exists and old password is equal to hashed password
	private function uidExists() {
		$result;
		if(!$this->checkUser($this->uid, $this->oldPwd))
		{
			$result = false;
		} 
		else
		{
			$result = true;
		}
		return $result;
	}



}