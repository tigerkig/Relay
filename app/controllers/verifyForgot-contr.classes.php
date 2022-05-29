<?php
class VerifyForgotContr extends VerifyForgot {

	private $code;
    private $email;

	public function __construct($code, $email) {
		$this->code = $code;
		$this->email = $email;
	}

	public function verifyForgotUser() {
		if($this->emptyInput() == false) {
			$_SESSION['error'] = 'Please enter the code from the email';
			header('location: ../index.php?verify');
			exit();
		}

		$this->getVerifyForgotUser($this->code, $this->email);
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