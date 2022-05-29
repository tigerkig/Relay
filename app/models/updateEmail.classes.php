<?php
class UpdateEmail extends Dbh {
	
	use SendMail;

	protected function updateEmail($uid, $email) { // update user's email

		$stmt = $this->connect()->prepare('UPDATE users SET email = ? WHERE id = ?;'); // connect to database
		if(!$stmt->execute(array($email, $uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../dashboard.php');
			exit();
		}

		// close connection
		$stmt = null;

		// set the new email session
		$_SESSION['EMAIL'] = $email;
	
		// email confirmation sent to user when email is updated
		$to = $_SESSION['EMAIL'];
		$subject = "Relay - Update Email Confirmation";
		$msg = "<p>Hey ".$_SESSION['USERNAME'].", you changed your email to ".$email."</p>";

		if(!$this->sendMail($to,$subject,$msg)) {
			$stmt = null;		
			$_SESSION['error'] = 'Error! Could not send a confirmation email to your old email address';
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

	// check if email already exists
	protected function getUser($email) {

		$stmt = $this->connect()->prepare('SELECT email FROM users WHERE email = ?;'); // connect to database

		if(!$stmt->execute(array($email))) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../index.php');
			exit();
		}

		$resultCheck; // declare variable
		if($stmt->rowCount() > 0) {	// check if email exists
			$resultCheck = false;
		} else {
			$resultCheck = true;
		}

		return $resultCheck;

	}
	
}