<?php
class Logout extends Dbh {

	protected function updateUser($uid) { // logout the user

		// prepare to delete authentication token with the user's uid
		$stmt = $this->connect()->prepare('DELETE FROM auth_tokens WHERE userid = ?;'); // connect to database

		// if database connection fails
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../index.php');
			exit();
		}

		// close connection
		$stmt = null;

		// Delete cookie by setting the date to a day before today (expiration)
		if(isset($_COOKIE['remember']))
		{
			setcookie("remember", "", time() - 7200, '/');
		}

		// we now will set the user to be offline
		$offline = 0;

		// prepare to update the user's online status to offline
		$stmt = $this->connect()->prepare('UPDATE users SET online_status = ? WHERE id = ?;'); // connect to database

		// if database connection fails
		if(!$stmt->execute(array($offline, $uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../index.php');
			exit();
		}

		// close connection
		$stmt = null;

		// destroy user's sessions
		session_destroy();

		// throw message and redirect to login view
		$_SESSION['success'] = 'You are now logged out, please come back soon!';
		header('location: ../index.php');
		exit();

	}
	
}