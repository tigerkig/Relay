<?php
class UpdatePreferences extends Dbh {
	
	use SendMail;

	protected function updatePreferences($uid, $notify, $security) { // update user's email

		if($notify == 'on') {
			$notify = 1;
		} else {
			$notify = 0;
		}

		if($security == 'on') {
			$security = 1;
		} else {
			$security = 0;
		}

		$stmt = $this->connect()->prepare('UPDATE users SET notify = ?, 2fa = ? WHERE id = ?;'); // connect to database
		if(!$stmt->execute(array($notify, $security, $uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../dashboard.php');
			exit();
		}

		// close connection
		$stmt = null;
	
		// email confirmation sent to user when email is updated
		$to = $_SESSION['EMAIL'];
		$subject = "Relay - Update Email Confirmation";
		$msg = "<p>Hey ".$_SESSION['USERNAME'].", you updated your preferences</p>";

		if(!$this->sendMail($to,$subject,$msg)) {
			$stmt = null;		
			$_SESSION['error'] = 'Error! Could not send a confirmation email';
			header('location: ../dashboard.php');
			exit();
		}
		
		$stmt = null; // close connection

	}

	protected function checkUser($uid, $pwd) { // check if id and password match

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
		$verifyPwd = password_verify($pwd, $hashedPwd[0]['password']);
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