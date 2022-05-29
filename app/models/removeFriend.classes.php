<?php
class RemoveFriend extends Dbh {

	protected function removeFriend($friendUid, $uid) {

		/*
			1. Delete user from friend's friend's list
		*/

		// fetch friend's info
		$friend = $this->getUserInfo($friendUid);


		// call the function to remove user from friend's list and store new list
		$friendsList = $this->removeFromFriendsList($friend['friends'], $uid);

		// call the function to update friend's friend list
		if(!$this->updateFriendsList($friendsList, $friendUid))
		{
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		/*
			2. Delete friend from user's friend's list
		*/

		// fetch friend's info
		$user = $this->getUserInfo($uid);

		// call the function to remove user from friend's list and store new list
		$friendsList = $this->removeFromFriendsList($user['friends'], $friendUid);

		// call the function to update friend's friend list
		if(!$this->updateFriendsList($friendsList, $uid))
		{
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		/*
			3. Delete conversation between the two users
		*/	

		// call the function to delete the conversation between the two users
		if(!$this->deleteConversation($friendUid, $uid))
		{
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// if there are no errors from steps 1 to 3
		$alert['message'] = 'You have removed '.$friend['username'].' as a friend';
        $alert['type'] = 'success';

		// send back a success alert
		echo json_encode($alert);
		exit();

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

	// iterate through friend's list and remove the user from it
	private function removeFromFriendsList($friendsList, $uid) {

		// store friends's list of friends in array
		$friendsList = explode(',', $friendsList); // ex friend

		// iterate through friend's list of friends
		foreach($friendsList as $key => $id)
		{
			// check if any of the friend's id's match $uid (friend we want to remove)
			if($id == $uid)
			{
				unset($friendsList[$key]);
			}
		}

		// take the converted array and convert back to string format separated by comma
		$friendsList = implode(',', $friendsList);

		// return the friends list
		return $friendsList;

	}

	// fetch the user's info
	private function updateFriendsList($friendList, $uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('UPDATE users SET friends = ? WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($friendList, $uid))) 
		{
			$stmt = null;
			return false;
		}
		
		// close connection
		$stmt = null;
		
		// return true if friend's list has been updated
		return true;

	}

	// delete conversation between two users
	private function deleteConversation($friendUid, $uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('DELETE FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?);'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid, $friendUid, $friendUid, $uid))) 
		{
			$stmt = null;
			return false;
		}

		// close connection
		$stmt = null;
		
		// return true if the conversation has been deleted
		return true;

	}

}