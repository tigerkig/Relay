<?php
class WelcomeScreenContr extends WelcomeScreen {

	private $uid;
	private $firstLogin;
	private $loggedIn;

	public function __construct($uid, $firstLogin, $loggedIn) {
		$this->uid = $uid;
		$this->firstLogin = $firstLogin;
		$this->loggedIn = $loggedIn;
	}

	public function welcomeScreenUser() {
		// check if receiver username is empty
		if($this->emptyValues() == false) {
			$_SESSION['error'] = 'Error! You need to be logged in to do this action';
			header('location: ../index.php');
			exit();
		}

		// check if user is not logged in
		if($this->notLoggedIn() == false) {
			$_SESSION['error'] = 'Error! You need to be logged in to do this action';
			header('location: ../index.php');
			exit();
		}

		// check if first login is already set to 0 (not their first login
		if($this->notFirstLogin() == false) {
			header('location: ../dashboard.php');
			exit();
		}
		
		// if there are no errors -> call fetchData function
		$this->welcomeScreen($this->uid, $this->firstLogin, $this->loggedIn);
	}

	// check if session data is empty
	private function emptyValues() {
		$result;
		if(empty($this->uid) || empty($this->firstLogin) || empty($this->loggedIn))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if first login is not 1
	private function notLoggedIn() {
		$result;
		if(!$this->loggedIn)
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if first login is not 1
	private function notFirstLogin() {
		$result;
		if($this->firstLogin == 0)
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