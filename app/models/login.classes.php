<?php
class Login extends Dbh {
	
	use SendMail;

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
			header('location: ../index.php');
			exit();
		}

		$stmt = null; // close connection

	}

	protected function getUser($uid, $pwd, $remember) { // login the user

		$stmt = $this->connect()->prepare('SELECT password FROM users WHERE username = ? OR email = ?;'); // connect to database

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

		$hashedPwd = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$verifyPwd = password_verify($pwd, $hashedPwd[0]['password']);

		if($verifyPwd == false) // if password is wrong
		{
			$stmt = null;
			$_SESSION['error'] = 'The password you entered does not match';
			$_SESSION['tempUid'] = $uid;
			header('location: ../index.php');
			exit();
		}
		elseif($verifyPwd == true) // if password matches
		{

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

			// if 2fa is turned off
			if($user[0]['2fa'] == 0) 
			{
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


			}
			// if 2fa is turned on
			else
			{
				$stmt = null;

				$selector = bin2hex(random_bytes(8));
				$_SESSION['token'] = random_bytes(32);
				$_SESSION['loggedin'] = 2;
				$_SESSION['remember'] = $remember;
				$_SESSION['EMAIL'] = $user[0]['email'];
				$_SESSION['verifyType'] = 'login';
				$code = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
				$expires = date("U") + 3600;
				$hashedToken = password_hash($_SESSION['token'], PASSWORD_DEFAULT);

				// delete old 2fa token
				$stmt = $this->connect()->prepare('DELETE FROM email_tokens WHERE user_id = ?;'); // connect to database
				if(!$stmt->execute(array($user[0]['email']))) 
				{
					$stmt = null;
					$_SESSION['error'] = 'Connection to database failed';
					header('location: ../index.php');
					exit();
				}

				// insert new 2fa token
				$stmt = $this->connect()->prepare('INSERT INTO email_tokens (user_id, selector, token, code, expires) VALUES (?, ?, ?, ?, ?);'); // connect to database
				if(!$stmt->execute(array($user[0]['email'],$selector,$hashedToken,$code,$expires))) 
				{
					$stmt = null;
					$_SESSION['error'] = 'Connection to database faileds';
					header('location: ../index.php');
					exit();
				}
				
				// email login verification code to user
				$to = $user[0]['email'];
				$subject = "Relay - Login Verification Code";
				$msg = "<p>Here is your login verification code <strong>".$code."</strong></p>";

				if($this->sendMail($to,$subject,$msg)) {
					$_SESSION['success'] = 'Your account has 2FA enabled. Please check your email for a login verification code and please type it in the form below';
					header('location: ../index.php?verify');
				} else {
					$_SESSION['error'] = 'Error! Could not send 2FA email';
				}
				
				exit();
			}
			
			$stmt = null; // close connection

		}

	}
	
}