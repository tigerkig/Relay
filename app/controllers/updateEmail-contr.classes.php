<?php
class UpdateEmailContr extends UpdateEmail {

	private $uid;
    private $email;
    private $pwd;

	public function __construct($uid, $email, $pwd) {
		$this->uid = $uid;
        $this->email = $email;
        $this->pwd = $pwd;
	}

	public function updateEmailUser() {
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
		if($this->invalidEmail() == false) {
			$_SESSION['error'] = 'Error! You must enter a valid email';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->pwdMatch() == false) {
			$_SESSION['error'] = 'Error! The password you entered does not match';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->uidExists() == false) {
			$_SESSION['error'] = 'Error! Email is already in use';
			header('location: ../dashboard.php');
			exit();
		}

		$this->updateEmail($this->uid, $this->email);
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

	// check if email or password is empty
	private function emptyInput() {
		$result;
		if(empty($this->email) || empty($this->pwd))
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

	// Check if password is the same as the account
	private function pwdMatch() {
		$result;
		if(!$this->checkUser($this->uid, $this->pwd))
		{
			$result = false;
		} 
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if email exists and is already taken
	private function uidExists() {
		$result;
		if(!$this->getUser($this->email))
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