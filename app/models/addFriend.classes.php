<?php
class AddFriend extends Dbh {

	protected function addFriend($receiver, $sender, $senderUid) {

		// fetch receiver uid for later use
		$receiverUid = $this->getReceiverUid($receiver);

		// fetch receiver uid for later use
		$friends = explode(',',$this->getFriendsList($senderUid));

		// check if receiver uid is in sender's friend's list
		if($this->in_array_r($receiverUid, $friends)) 
		{
			$stmt = null;
			$alert['message'] = 'Error! '.$receiver.' is already your friend';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// prepare statement to check if already existing friend request between receiver and sender
		$stmt = $this->connect()->prepare('SELECT * FROM friend_request WHERE sender_id = ? AND receiver_id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($senderUid, $receiverUid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// if there is already an outgoing friend request 
		if($stmt->rowCount() > 0)
		{	
			$stmt = null;
			$alert['message'] = 'You have already sent '.$receiver.' a friend request';
			$alert['type'] = 'info';
			echo json_encode($alert);
			exit();
		}

		// if there is not an outgoing friend request
		$stmt = null;

		// prepare statement to insert a new outgoing friend request
		$stmt = $this->connect()->prepare('INSERT INTO friend_request (sender_id, receiver_id) VALUES (?, ?);'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($senderUid, $receiverUid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		else
		{
			$stmt = null;
			$alert['message'] = 'Success! You sent '.$receiver.' a friend request';
        	$alert['type'] = 'success';	
			echo json_encode($alert);
			exit();
		}
	
	}
		


	// fetch the receiver's uid
	private function getReceiverUid($receiver) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT id FROM users WHERE username = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($receiver))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// fetch receiver's uid
		if(!$user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			$alert['message'] = 'Error! User does not exist';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// return receiver's uid
		return $user[0]['id'];

	}

	// fetch sender's friend list
	private function getFriendsList($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT friends FROM users WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's friend's list
		if(!$user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			$alert['message'] = 'Error! User does not exist';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// return user's friend's list
		return $user[0]['friends'];

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