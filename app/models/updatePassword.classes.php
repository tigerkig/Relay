<?php

class UpdatePassword extends Dbh {

	use SendMail;

	protected function updatePassword($uid, $pwd) { // insert new user into database

		// encrypt the password by hashing
		$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

		// Update password
		$stmt = $this->connect()->prepare('UPDATE users SET password = ? WHERE id = ?;'); // connect to database
		if(!$stmt->execute(array($hashedPwd, $uid))) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../dashboard.php');
			exit();
		}
		
		// close connection
		$stmt = null;
	
		// email confirmation sent to user when email is updated
		$to = $_SESSION['EMAIL'];
		$subject = "Relay - Update Password Confirmation";
		$msg = "<p>Hey ".$_SESSION['USERNAME'].", you updated your password.</p>";

		if(!$this->sendMail($to,$subject,$msg)) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Could not send a confirmation email';
			header('location: ../dashboard.php');
			exit();
		}
		
		$stmt = null; // close connection
		
	}

	protected function checkUser($uid, $oldPwd) { // check if id and password match

		$stmt = $this->connect()->prepare('SELECT password FROM users WHERE id = ?;'); // connect to database

		if(!$stmt->execute(array($uid))) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../dashboard.php');
			exit();
		}

		if($stmt->rowCount() == 0) // check if username or email exists
		{	
			$stmt = null;
			$_SESSION['error'] = 'User does not exist';
			header('location: ../dashboard.php');
			exit();
		}

		$hashedPwd = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$verifyPwd = password_verify($oldPwd, $hashedPwd[0]['password']);
		$resultCheck; // declare variable

		if($verifyPwd == false) // if password is wrong
		{
			$resultCheck = false;
		}
		elseif($verifyPwd == true) // if password matches
		{
			$resultCheck = true;
		}

		return $resultCheck;

	}
	
}