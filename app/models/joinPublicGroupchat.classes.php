<?php

class JoinPublicGroupchat extends Dbh {

	protected function joinPublicGroupchat($uid, $groupid) { // insert new user into database
		
		// fetch groupchat members list
		$groupchat = $this->fetchGroupchatInfo($groupid);

		// explode list
		$memberList = explode(',',$groupchat['members']);
		
		$_SESSION['testUID'] = array($uid, $memberList);

		if($this->in_array_r($uid, $memberList)) 
		{
			$stmt = null;
			$alert['message'] = 'Error! You are already in '.$groupchat['name'].'!';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		array_push($memberList, $uid);

		$memberList = implode(',',$memberList);

		// Update groupchat and add uid to members list
		$stmt = $this->connect()->prepare('UPDATE groupchat SET members = ? WHERE id = ?;'); // connect to database
		
		if(!$stmt->execute(array($memberList,$groupid))) {
			$stmt = null;
			$alert['message'] = 'Error! Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch users groupchat list
		$user = $this->fetchUserInfo($uid);

		// explode list
		$groupchatList = explode(',',$user['groupchats']);

		if($this->in_array_r($uid, $groupchatList)) {
			$stmt = null;
			$alert['message'] = 'Error! You are already in '.$groupchat['name'].'!';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		array_push($groupchatList, $groupid);

		$groupchatList = implode(',',$groupchatList);
		$_SESSION['groupchatList'] = array($groupchatList,$groupid);

		// Update groupchat and add uid to members list
		$stmt = $this->connect()->prepare('UPDATE users SET groupchats = ? WHERE id = ?;'); // connect to database
		
		if(!$stmt->execute(array($groupchatList,$uid))) {
			$stmt = null;
			$alert['message'] = 'Error! Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
			$_SESSION['poodoo'] = array($user,$groupchat);

		$stmt = null; // close connection

		// if there are no errors from steps 1 to 3
		$alert['message'] = 'You have joined '.$groupchat['name'].'!';
        $alert['type'] = 'success';

		// send back a success alert
		echo json_encode($alert);
		exit();
	
	}

	// fetch user's info
	private function fetchUserInfo($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM users WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
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
	private function fetchGroupchatInfo($groupid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM groupchat WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($groupid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// fetch user's info
		if(!$groupchat = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;		
		}
		
		// return user's info
		return $groupchat[0];

	}

	// check if receiver's uid is in sender's friend's list
	private function in_array_r($needle, $haystack, $strict = false) {
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
				return true;
			}
		}
	
		return false;
	}
	
}