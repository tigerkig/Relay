<?php
class VerifyLogin extends Dbh {

	private function rememberMe($uid) {

		$selector = base64_encode(random_bytes(9));
		$authenticator = random_bytes(33);
		$hashedToken = hash('sha256', $authenticator);
		$expires = date('Y-m-d\TH:i:s', time() + 864000);

		// set authentication cookie
		setcookie(
			'remember',
			$selector.':'.base64_encode($authenticator),
			time() + 864000,
			'/'
		);

		$stmt = $this->connect()->prepare('INSERT INTO auth_tokens (userid, selector, token, expires) VALUES (?, ?, ?, ?);'); // connect to database

		if(!$stmt->execute(array($uid, $selector, $hashedToken, $expires))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../index.php?verify');
			exit();
		}

		$stmt = null; // close connection

	}

	protected function getVerifyLoginUser($code, $email, $remember) { // login the user

		$stmt = $this->connect()->prepare('SELECT * FROM email_tokens WHERE user_id = ?'); // connect to database

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
			$_SESSION['error'] = 'Error! Please try logging in again';
			header('location: ../index.php?verify');
			exit();
		}

		$token = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if($token[0]['expires'] && time() > $token[0]['expires']+3600) // if token expired
        {
            $stmt = null;
            $_SESSION['error'] = 'Error! Your token has expired, please try logging in again';
            header('location: ../index.php');
            exit();
        }

		if($code != $token[0]['code']) // if code is wrong
		{
			$stmt = null;
			$_SESSION['error'] = 'Error! You have entered the wrong code';
			header('location: ../index.php?verify');
			exit();
		}
		else // if password matches
		{
            // $stmt = null;
			$stmt = $this->connect()->prepare('SELECT * FROM users WHERE email = ?;'); // connect to database
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
				$_SESSION['error'] = 'Error! Email not found';
				header('location: ../index.php');
				exit();
			}

			
			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // if user clicked remember me then call rememberMe function
            if($remember) 
            {
                $this->rememberMe($user[0]['id']);			
            }

            // create user session
            $_SESSION['loggedin'] = 1;
            $_SESSION['UID'] = $user[0]['id'];
            $_SESSION['USERNAME'] = $user[0]['username'];
            $_SESSION['EMAIL'] = $user[0]['email'];
            $_SESSION['first_login'] = $user[0]['first_login'];
            $_SESSION['security'] = $user[0]['2fa'];
            $_SESSION['verify'] = $user[0]['verify'];
            $_SESSION['notify'] = $user[0]['notify'];
            $_SESSION['online_status'] = 1;

            if($user[0]['first_login'] == 1) {
                $_SESSION['success'] = 'Hello '.$user[0]['username'].', welcome to liveChat!';
            } else {
                $_SESSION['success'] = 'Welcome back, '.$user[0]['username'].'!';
            }
            
            unset($_SESSION['tempUid']);
            unset($_SESSION['token']);
            unset($_SESSION['remember']);
			unset($_SESSION['verifyType']);

			$stmt = $this->connect()->prepare('DELETE FROM email_tokens WHERE user_id = ?;'); // connect to database
			if(!$stmt->execute(array($email))) 
			{
				$stmt = null;
				$_SESSION['error'] = 'Connection to database failed';
				header('location: ../dashboard.php');
				exit();
			}
			
			header('location: ../dashboard.php');
			
			exit();
		}

		$stmt = null; // close connection

	}
	
}