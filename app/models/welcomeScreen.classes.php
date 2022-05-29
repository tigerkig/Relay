<?php
class WelcomeScreen extends Dbh {

	protected function welcomeScreen($uid, $firstLogin, $loggedIn) {

		// fetch receiver uid for later use
		if(!$receiverUid = $this->updateUser($uid)) {
			$_SESSION['error'] = 'Error! The server is having trouble connecting to the database';
			header('location: ../index.php');
			exit();
		}

		// throw a success message
		$_SESSION['success'] = 'Success! Welcome to Relay start by adding some friends to chat with!';
		header('location: ../dashboard.php');
		exit();

	}
		


	// fetch the receiver's uid
	private function updateUser($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('UPDATE users SET first_login = ? WHERE id = ?;'); // connect to database

		$setFirstLogin = 0;
		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($setFirstLogin, $uid))) 
		{
			$stmt = null;
			return false;
		}
		
		// return receiver's uid
		return true;

	}

}