<?php
class DeleteAccountContr extends DeleteAccount {

	private $uid;
    private $email;
    private $pwd;
	private $username;

	public function __construct($uid, $email, $pwd, $username) {
		$this->uid = $uid;
		$this->email = $email;
        $this->pwd = $pwd;
		$this->username = $username;
	}

	public function deleteAccountUser() {
		if($this->emptyUid() == false) {
			$_SESSION['error'] = 'Error! You need to log in again';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->emptyInput() == false) {
			$_SESSION['error'] = 'Please fill in your password';
			header('location: ../dashboard.php');
			exit();
		}
		if($this->pwdMatch() == false) {
			$_SESSION['error'] = 'Error! The password you entered does not match';
			header('location: ../dashboard.php');
			exit();
		}

		$this->deleteAccount($this->uid, $this->email, $this->username);
	}

	// check if uid isn't set
	private function emptyUid() {
		$result;
		if(empty($this->uid) || empty($this->email) || empty($this->username))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if password is empty
	private function emptyInput() {
		$result;
		if(empty($this->pwd))
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

}