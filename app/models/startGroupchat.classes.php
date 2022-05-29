<?php

class StartGroupchat extends Dbh {

	protected function startGroupchat($uid, $name, $desc, $type) { // insert new user into database
		
		// Create new group chat
		$stmt = $this->connect()->prepare('INSERT INTO groupchat (members, owner, name, type, description) VALUES (?, ?, ?, ?, ?);'); // connect to database
		
		if(!$stmt->execute(array($uid, $uid, $name, $type, $desc))) {
			$stmt = null;
			$alert['message'] = 'Error! Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// get last inserted id of groupchat
		$stmt = $this->connect()->prepare('SELECT * FROM groupchat WHERE id=(select MAX(id) from groupchat);'); // connect to database
		
		if(!$stmt->execute()) {
			$stmt = null;
			$alert['message'] = 'Error! Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// store groupchat id in variable
		$groupchatId = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$groupchatId = $groupchatId[0]['id'];

		// get user's data
		$stmt = $this->connect()->prepare('SELECT groupchats FROM users WHERE id = ?;'); // connect to database

		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$userGroupchats = explode(',',$user[0]['groupchats']);

		// remove any null groupchats on list
		for($i = 0 ; $i <= count($userGroupchats) ; $i++) {
			if(!$userGroupchats[$i]) {
				unset($userGroupchats[$i]);
			}
		}

		if(!empty($userGroupchats)) {
			array_push($userGroupchats, $groupchatId);
			$userGroupchats = implode(',',$userGroupchats);

		} else {
			$userGroupchats = $groupchatId;
		}

		// Update groupchat list for user
		$stmt = $this->connect()->prepare('UPDATE users SET groupchats = ? WHERE id = ?;'); // connect to database
		if(!$stmt->execute(array($userGroupchats, $uid))) {
			$stmt = null;
			$alert['message'] = 'Error! Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		$stmt = null; // close connection

		// if there are no errors from steps 1 to 3
		$alert['message'] = 'You have started a new group chat!';
        $alert['type'] = 'success';

		// send back a success alert
		echo json_encode($alert);
		exit();

		
		
	}
	
}