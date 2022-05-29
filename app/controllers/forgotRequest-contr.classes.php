<?php
class ForgotRequestContr extends ForgotRequest {

	private $uid;

	public function __construct($uid) {
		$this->uid = $uid;
	}

	public function forgotRequestUser() {
		if($this->emptyInput() == false) {
			$_SESSION['error'] = 'Please fill in your username or email';
			header('location: ../index.php?forgot');
			exit();
		}

		$this->getForgotRequestUser($this->uid);
	}

	private function emptyInput() {
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