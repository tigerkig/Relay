<?php
class ChangePassword extends Dbh {
	use SendMail;

	protected function getChangePasswordUser($pwd, $pwdRepeat, $code, $email) { // login the user

        $currentDate = date("U");
        $hash = password_hash($pwd, PASSWORD_DEFAULT);

		$stmt = $this->connect()->prepare('SELECT * FROM pwdReset WHERE pwdResetUser = ? AND pwdResetCode=? AND pwdResetExpires >= ?;'); // connect to database
        
		if(!$stmt->execute(array($email, $code, $currentDate))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../index.php?changePassword');
			exit();
		}

		if($stmt->rowCount() == 0) // check if username or email exists
		{	
			$stmt = null;
			$_SESSION['error'] = 'Error! You need to resend a new password reset request';
			header('location: ../index.php?forgot');
			exit();
		}

        $stmt = $this->connect()->prepare('SELECT * FROM users WHERE username = ? OR email = ?;'); // connect to database
        if(!$stmt->execute(array($email, $email))) 
        {
            $stmt = null;
            $_SESSION['error'] = 'Connection to database failed';
            header('location: ../index.php?changePassword');
            exit();
        }

        if($stmt->rowCount() == 0) // check if email exists
        {	
            $stmt = null;
            $_SESSION['error'] = 'Username or email not found';
            header('location: ../index.php?changePassword');
            exit();
        }

			
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // update password
        
        $stmt = $this->connect()->prepare('UPDATE users SET password = ? WHERE email = ? OR username = ?;'); // connect to database
        if(!$stmt->execute(array($hash, $email, $email))) 
        {
            $stmt = null;
            $_SESSION['error'] = 'Connection to database failed';
            header('location: ../index.php?changePassword');
            exit();
        }

        $stmt = $this->connect()->prepare('DELETE FROM pwdReset WHERE pwdResetUser = ?;'); // connect to database
        if(!$stmt->execute(array($email))) 
        {
            $stmt = null;
            $_SESSION['error'] = 'Connection to database failed';
            header('location: ../index.php?changePassword');
            exit();
        }			

		// delete old forgot password token
		$stmt = $this->connect()->prepare('DELETE FROM pwdReset WHERE pwdResetUser = ?;'); // connect to database
		if(!$stmt->execute(array($email))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../index.php?forgot');
			exit();
		}
		
		// email password reset confirmation to user
		$to = $user[0]['email'];
		$subject = "Relay - Password Reset Confirmation";
		$msg = "<p>You have changed your Relay account password</p>";

		if($this->sendMail($to,$subject,$msg)) {
			$_SESSION['success'] = 'Success! You have reset your password, you may now login';
			unset($_SESSION['code']);
			unset($_SESSION['EMAIL']);
			header('location: ../index.php');
		} else {
			$_SESSION['error'] = 'Error! Could not send password reset confirmation';
			header('location: ../index.php');
		}
		
		exit();
				
		$stmt = null; // close connection	

	}
	
}