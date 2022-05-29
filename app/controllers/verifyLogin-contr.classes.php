<?php
class VerifyLoginContr extends VerifyLogin {

	private $code;
    private $email;
	private $remember;

	public function __construct($code, $email, $remember) {
		$this->code = $code;
		$this->email = $email;
		$this->remember = $remember;
	}

	public function verifyLoginUser() {
		if($this->emptyInput() == false) {
			$_SESSION['error'] = 'Please enter the code from the email';
			header('location: ../index.php?verify');
			exit();
		}

		$this->getVerifyLoginUser($this->code, $this->email, $this->remember);
	}

	private function emptyInput() {
		$result;
		if(empty($this->code))
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