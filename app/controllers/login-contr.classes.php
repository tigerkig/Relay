<?php
class LoginContr extends Login {

	private $uid;
	private $pwd;
	private $remember;

	public function __construct($uid, $pwd, $remember) {
		$this->uid = $uid;
		$this->pwd = $pwd;
		$this->remember = $remember;
	}

	public function loginUser() {
		if($this->emptyInput() == false) {
			$_SESSION['error'] = 'Please fill out the entire form';
			header('location: ../index.php');
			exit();
		}

		$this->getUser($this->uid, $this->pwd, $this->remember);
	}

	private function emptyInput() {
		$result;
		if(empty($this->uid) || empty($this->pwd))
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