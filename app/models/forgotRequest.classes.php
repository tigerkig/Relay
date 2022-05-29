<?php
class ForgotRequest extends Dbh {
	use SendMail;

	protected function getForgotRequestUser($uid) { // login the user

		$stmt = $this->connect()->prepare('SELECT password FROM users WHERE username = ? OR email = ?;'); // connect to database

		if(!$stmt->execute(array($uid, $uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../index.php?forgot');
			exit();
		}

		if($stmt->rowCount() == 0) // check if username or email exists
		{	
			$stmt = null;
			$_SESSION['error'] = 'Error! Username or email not found';
			header('location: ../index.php?forgot');
			exit();
		}



		$stmt = $this->connect()->prepare('SELECT * FROM users WHERE username = ? OR email = ?;'); // connect to database
		if(!$stmt->execute(array($uid, $uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../index.php');
			exit();
		}

		if($stmt->rowCount() == 0) // check if username or email exists
		{	
			$stmt = null;
			$_SESSION['error'] = 'Username or email not found';
			header('location: ../index.php');
			exit();
		}

		
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$selector = bin2hex(random_bytes(8));
		$_SESSION['token'] = random_bytes(32);
		$_SESSION['EMAIL'] = $uid;
		$_SESSION['verifyType'] = 'forgot';
		$code = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
		$expires = date("U") + 3600;
		$hashedToken = password_hash($_SESSION['token'], PASSWORD_DEFAULT);

		// delete old forgot password token
		$stmt = $this->connect()->prepare('DELETE FROM pwdReset WHERE pwdResetUser = ?;'); // connect to database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../index.php?forgot');
			exit();
		}

		// insert new password token
		$stmt = $this->connect()->prepare('INSERT INTO pwdReset (pwdResetUser, pwdResetSelector, pwdResetToken, pwdResetCode, pwdResetExpires) VALUES (?, ?, ?, ?, ?);'); // connect to database
		if(!$stmt->execute(array($uid,$selector,$hashedToken,$code,$expires))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database faileds';
			header('location: ../index.php?forgot');
			exit();
		}
			
		// email login verification code to user
		$to = $user[0]['email'];
		$subject = "Relay - Forgot Password Verification Code";
		$msg = "<p>Here is your forgot password verification code <strong>".$code."</strong></p>";

		if($this->sendMail($to,$subject,$msg)) {
			$_SESSION['success'] = 'Success! We have sent your email a forgot password token, please click the link in the email';
			header('location: ../index.php?verify');
		} else {
			$_SESSION['error'] = 'Error! Could not send forgot password token email';
			header('location: ../index.php?forgot');
		}
		
		exit();
		
		$stmt = null; // close connection

	}
	
}