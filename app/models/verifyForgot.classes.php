<?php
class VerifyForgot extends Dbh {

	protected function getVerifyForgotUser($code, $email) { // login the user

		$stmt = $this->connect()->prepare('SELECT * FROM pwdReset WHERE pwdResetUser = ?'); // connect to database

		if(!$stmt->execute(array($email))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../index.php?verify');
			exit();
		}

		if($stmt->rowCount() == 0) // check if username or email exists
		{	
			$stmt = null;
			$_SESSION['error'] = 'Error! Could not find the email or username requested';
			header('location: ../index.php?verify');
			exit();
		}

		$token = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($token[0]['pwdResetExpires'] && time() > $token[0]['pwdResetExpires']+3600) // if token expired
        {
            $stmt = null;
            $_SESSION['error'] = 'Error! Your token has expired, please try requesting a new password';
            header('location: ../index.php?forgot');
            exit();
        }

		if($code != $token[0]['pwdResetCode']) // if code is wrong
		{
			$stmt = null;
			$_SESSION['error'] = 'Error! You have entered the wrong code';
			header('location: ../index.php?verify');
			exit();
		}
		else // if password matches
		{
            // $stmt = null;
			$stmt = $this->connect()->prepare('SELECT * FROM users WHERE username = ? OR email = ?;'); // connect to database
			if(!$stmt->execute(array($email, $email))) 
			{
				$stmt = null;
				$_SESSION['error'] = 'Connection to database failed';
				header('location: ../index.php?forgot');
				exit();
			}

			if($stmt->rowCount() == 0) // check if username or email exists
			{	
				$stmt = null;
				$_SESSION['error'] = 'Error! Email or username not found';
				header('location: ../index.php?forgot');
				exit();
			}

			
			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);


            // create user session
            $_SESSION['EMAIL'] = $email;
            $_SESSION['code'] = $code;
            $_SESSION['success'] = 'Success! You may now change your password';
            
			unset($_SESSION['verifyType']);
			
			header('location: ../index.php?changePassword');
			
			exit();
		}

		$stmt = null; // close connection

	}
	
}