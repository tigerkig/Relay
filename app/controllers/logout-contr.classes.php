<?php
class LogoutContr extends Logout {

	private $uid;

	public function __construct($uid) {
		$this->uid = $uid;
	}

	public function logoutUser() {
		if($this->emptyUid() == false) {
			$_SESSION['error'] = 'Error! You are already logged out';
			header('location: ../index.php');
			exit();
		}

		$this->updateUser($this->uid);
	}

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

}