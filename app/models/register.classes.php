<?php

class Register extends Dbh {

	protected function pgpKeys($username, $email, $pwd) {

		// Key name and email
		$uid = $username+' <'+$email+'>';

		$rsa = new \phpseclib\Crypt\RSA();
		$k = $rsa->createKey(512);
		$rsa->loadKey($k['privatekey']);

		$nkey = new OpenPGP_SecretKeyPacket(array(
			'n' => $rsa->modulus->toBytes(),
			'e' => $rsa->publicExponent->toBytes(),
			'd' => $rsa->exponent->toBytes(),
			'p' => $rsa->primes[2]->toBytes(),
			'q' => $rsa->primes[1]->toBytes(),
			'u' => $rsa->coefficients[2]->toBytes()
		));

		$uid = new OpenPGP_UserIDPacket($uid);

		$wkey = new OpenPGP_Crypt_RSA($nkey);
		$m = $wkey->sign_key_userid(array($nkey, $uid));
		$m[0] = OpenPGP_Crypt_Symmetric::encryptSecretKey($pwd, $nkey);

		// ASCII private key
		$privkey = OpenPGP::enarmor($m->to_bytes(), "PGP PRIVATE KEY BLOCK");

		// ASCII public key
		$pubm = clone($m);
		$pubm[0] = new OpenPGP_PublicKeyPacket($pubm[0]);
		$pubkey = OpenPGP::enarmor($pubm->to_bytes(), "PGP PUBLIC KEY BLOCK");

		$keyArray = [];
		array_push($keyArray, $privkey, $pubkey);
		return $keyArray;
	}

	protected function setUser($uid, $pwd, $email, $notify) { // insert new user into database

		// Generate new password for key
		$keyPwd = bin2hex(openssl_random_pseudo_bytes(15));

		// generate private and public keys
		$keyArray = $this->pgpKeys($uid, $email, $keyPwd);

		$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT); // encrypt the password by hashing
		if($notify) {
			$notify = 1;
		} else {
			$notify = 0;
		}

		// Insert user
		$stmt = $this->connect()->prepare('INSERT INTO users (username, password, email, notify, publickey) VALUES (?, ?, ?, ?, ?);'); // connect to database
		if(!$stmt->execute(array($uid, $hashedPwd, $email, $notify, $keyArray[1]))) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../index.php?register');
			exit();
		}
		$stmt = null; // close connection

		// Insert private key
		$stmt = $this->connect()->prepare('INSERT INTO privatekeys (uid, privatekey) VALUES (?, ?);'); // connect to database
		if(!$stmt->execute(array($uid, $keyArray[0]))) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../index.php?register');
			exit();
		}
		$stmt = null; // close connection

		// Insert private key password
		$stmt = $this->connect()->prepare('INSERT INTO privatepwd (uid, pwd) VALUES (?, ?);'); // connect to database
		if(!$stmt->execute(array($uid, $keyPwd))) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../index.php?register');
			exit();
		}
		$stmt = null; // close connection
		
	}

	protected function checkUser($uid, $email) { // check if uid or email already exists

		$stmt = $this->connect()->prepare('SELECT username FROM users WHERE username = ? OR email = ?;'); // connect to database

		if(!$stmt->execute(array($uid, $email))) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../index.php?register');
			exit();
		}

		$resultCheck; // declare variable
		if($stmt->rowCount() > 0) {	// check if username or email exists
			$resultCheck = false;
		} else {
			$resultCheck = true;
		}

		return $resultCheck;

	}
	
}