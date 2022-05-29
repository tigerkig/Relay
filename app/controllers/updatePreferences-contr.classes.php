<?php
class UpdatePreferencesContr extends UpdatePreferences {

	private $uid;
    private $pwd;
    private $notify;
	private $security;

	public function __construct($uid, $pwd, $notify, $security) {
		$this->uid = $uid;
        $this->pwd = $pwd;
        $this->notify = $notify;
		$this->security = $security;
	}

	public function updatePreferencesUser() {
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

		$this->updatePreferences($this->uid, $this->notify, $this->security);
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