<?php
class ChangePasswordContr extends ChangePassword {

	private $pwd;
    private $pwdRepeat;
    private $code;
    private $email;

	public function __construct($pwd, $pwdRepeat, $code, $email) {
		$this->pwd = $pwd;
        $this->pwdRepeat = $pwdRepeat;
        $this->code = $code;
        $this->email = $email;
	}

	public function changePasswordUser() {
		if($this->emptyInput() == false) {
			$_SESSION['error'] = 'Please fill in the entire form';
			header('location: ../index.php?changePassword');
			exit();
		}
		if($this->pwdLength() == false) {
			$_SESSION['error'] = 'Error! Password must be a minimum of 8 characters';
			header('location: ../index.php?changePassword');
			exit();
		}
		if($this->pwdUpperLower() == false) {
			$_SESSION['error'] = 'Error! Password must contain atleast one uppercase and lowercase letter';
			header('location: ../index.php?changePassword');
			exit();
		}
		if($this->pwdNumber() == false) {
			$_SESSION['error'] = 'Error! Password must contain atleast one number';
			header('location: ../index.php?changePassword');
			exit();
		}
		if($this->pwdLettersNumbers() == false) {
			$_SESSION['error'] = 'Error! Password may only contain letters and numbers';
			header('location: ../index.php?changePassword');
			exit();
		}
		if($this->pwdMatch() == false) {
			$_SESSION['error'] = 'Error! Your passwords do not match';
			header('location: ../index.php?changePassword');
			exit();
		}

		$this->getChangePasswordUser($this->pwd, $this->pwdRepeat, $this->code, $this->email);
	}

	private function emptyInput() {
		$result;
		if(empty($this->pwd) || empty($this->pwdRepeat))
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

}