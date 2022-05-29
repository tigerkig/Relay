<?php
class FriendRequest extends Dbh {

	protected function friendRequest($response, $senderUid, $uid) {

		/*
			1. Check if there is a friend request in the database
		*/
		if(!$this->checkFriendRequest($senderUid, $uid))
		{
			$alert['message'] = 'Error! There is no friend request to respond to';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		/*
			2. Check if the response is accept or decline (finished and works)
		*/
		if($response == 'decline')
		{
			if(!$this->deleteFriendRequest($senderUid, $uid))
			{
				$alert['message'] = 'Error! Could not respond to friend request';
				$alert['type'] = 'error';
				echo json_encode($alert);
				exit();
			}

			exit();
		}

		// If the response is 'accept'
		if($response == 'accept')
		{

			/*
			2. For the receiver if response is accept
			*/

			// fetch receiver's friend list
			$friendsList = $this->getUserInfo($uid);

			// add the sender's uid to receiver's friend list
			$friendsList = $friendsList['friends'].','.$senderUid;

			// push the receiver's friend's list to the database
			if(!$this->updateFriendList($friendsList, $uid))
			{
				$alert['message'] = 'Error! Could not connect tdo database';
				$alert['type'] = 'error';
				echo json_encode($alert);
				exit();
			}

			/*
				3. For the sender if response is accept
			*/

			// fetch sender's friend list
			$friendsList = $this->getUserInfo($senderUid);

			// add the receiver's uid to receiver's friend list
			$friendsList = $friendsList['friends'].','.$uid;

			// push the sender's friend's list to the database
			if(!$this->updateFriendList($friendsList, $senderUid))
			{
				$alert['message'] = 'Error! Could not connect to datsabase';
				$alert['type'] = 'error';
				echo json_encode($alert);
				exit();
			}

			/*
				4. Delete the friend requests between the two users
			*/
			if(!$this->deleteFriendRequest($senderUid, $uid))
			{
				$alert['message'] = 'Error! Could not respond to friend request';
				$alert['type'] = 'error';
				echo json_encode($alert);
				exit();
			}

			// if there are no errors return success message
			$alert['message'] = 'Success! You are now friends with '.$friendsList['username'];
            $alert['type'] = 'success';
			echo json_encode($alert);
			exit();
		}

	}
		
	// check database for a friend request
	private function checkFriendRequest($senderUid, $uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM friend_request WHERE sender_id = ? AND receiver_id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($senderUid, $uid))) 
		{
			$stmt = null;
			return false;
		}

		// return true if there is a friend request
		return true;

	}

	// delete friend request
	private function deleteFriendRequest($senderUid, $uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('DELETE FROM friend_request WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?);'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($senderUid, $uid, $uid, $senderUid))) 
		{
			$stmt = null;
			return false;
		}

		// close connection
		$stmt = null;
		
		// return true if the conversation has been deleted
		return true;

	}

	// fetch the user's info
	private function getUserInfo($uid) {

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
		
		// fetch receiver's uid
		if(!$user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			$alert['message'] = 'Error! User does not exist';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// return receiver's uid
		return $user[0];

	}

	// update friend's list to database
	private function updateFriendList($friendsList, $uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('UPDATE users SET friends = ? WHERE id = ?;');

		// if prepared statement can't connect to database
		if(!$stmt->execute(array($friendsList, $uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			return false;
		}

		// if there are not errors
		return true;

	}

}