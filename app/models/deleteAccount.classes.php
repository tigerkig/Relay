<?php
class DeleteAccount extends Dbh {

	protected function deleteAccount($uid, $email, $username) { // update user's email

		// delete authtoken
		$stmt = $this->connect()->prepare('DELETE FROM auth_tokens WHERE userid = ?;'); // connect to database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed1';
			header('location: ../dashboard.php');
			exit();
		}

		// delete any forgot password token
		$stmt = $this->connect()->prepare('DELETE FROM pwdReset WHERE pwdResetUser = ?;'); // connect to database
		if(!$stmt->execute(array($email))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed2';
			header('location: ../dashboard.php');
			exit();
		}

		// delete any email token
		$stmt = $this->connect()->prepare('DELETE FROM email_tokens WHERE user_id = ?;'); // connect to database
		if(!$stmt->execute(array($email))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed3';
			header('location: ../dashboard.php');
			exit();
		}

		// delete friend requests
		$stmt = $this->connect()->prepare('DELETE FROM friend_request WHERE sender_id = ? OR receiver_id = ?;'); // connect to database
		if(!$stmt->execute(array($uid,$uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed4';
			header('location: ../dashboard.php');
			exit();
		}

		// delete private keys
		$stmt = $this->connect()->prepare('DELETE FROM privatekeys WHERE uid = ?;'); // connect to database
		if(!$stmt->execute(array($username))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed5';
			header('location: ../dashboard.php');
			exit();
		}

		// delete private pwd key
		$stmt = $this->connect()->prepare('DELETE FROM privatepwd WHERE uid = ?;'); // connect to database
		if(!$stmt->execute(array($username))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed6';
			header('location: ../dashboard.php');
			exit();
		}

		// delete messages
		$stmt = $this->connect()->prepare('DELETE FROM messages WHERE sender_id = ? OR receiver_id = ?;'); // connect to database
		if(!$stmt->execute(array($uid,$uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed7';
			header('location: ../dashboard.php');
			exit();
		}

		// get user's info
		$user = $this->getUserInfo($uid);

		// explode the friend list to an array
		$friends = explode(',',$user['friends']);

		// remove any null friend's on list
		for($i = 0 ; $i <= count($friends) ; $i++) {
			if(!$friends[$i]) {
				unset($friends[$i]);
			}
		}

		// below we will iterate through each of the user's friends
		foreach($friends as $friend) {

			// fetch the friend's friend list to remove the user from the list
			$friendList = $this->getFriendList($friend);

			// explode the friend list
			$friendList = explode(',',$friendList['friends']);

			// remove any null friend's on list
			for($i = 0 ; $i <= count($friendList) ; $i++) {
				if(!$friendList[$i]) {
					unset($friendList[$i]);
				}
			}

			// remove user from the friend's list
			foreach (array_keys($friendList, $uid) as $key) {
				unset($friendList[$key]);
			}

			// implode the friend list
			$friendList = implode(',',$friendList);

			// update the friend's friend list now that we removed the user's uid from the list
			$stmt = $this->connect()->prepare('UPDATE users SET friends = ? WHERE id = ?;'); // connect to database
			if(!$stmt->execute(array($friendList, $friend))) 
			{
				$stmt = null;
				$_SESSION['error'] = 'Connection to database failed';
				header('location: ../dashboard.php');
				exit();
			}

		}
		
		// delete user and remove the user's id from their friend's friend list
		$stmt = $this->connect()->prepare('DELETE FROM users WHERE id = ?;'); // connect to database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Connection to database failed';
			header('location: ../dashboard.php');
			exit();
		}

		// delete cookie by setting the date to a day before today (expiration)
		if(isset($_COOKIE['remember']))
		{
			setcookie("remember", "", time() - 7200, '/');
		}

		// delete sessions
		session_destroy();

		// close connection
		$stmt = null;

	}

	// fetch user's info
	private function getUserInfo($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM users WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Error! The server is having trouble connecting to the database';
			header('location: ../dashboard.php');
			exit();
		}
		
		// fetch user's info
		if(!$user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;		
		}
		
		// return user's info
		return $user[0];

	}

	// fetch user's info
	private function getFriendList($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT friends FROM users WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$_SESSION['error'] = 'Error! The server is having trouble connecting to the database';
			header('location: ../dashboard.php');
			exit();
		}
		
		// fetch user's info
		if(!$user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;		
		}
		
		// return user's info
		return $user[0];

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