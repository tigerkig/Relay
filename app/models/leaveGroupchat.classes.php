<?php
class LeaveGroupchat extends Dbh {

	protected function leaveGroupchat($groupid, $uid) {
		/*
			1. Delete user from groupchat's members's list
		*/

		// fetch friend's info
		$groupchat = $this->getGroupchatInfo($groupid);

		$countAdmins = explode(',',$groupchat['owner']);

		// if there is only one admin
		if(count($countAdmins) < 2 && in_array($uid,$countAdmins))
		{
			$alert['message'] = 'Error! You are the only admin, you must delete the group instead of leaving';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// call the function to remove user from groupchat's member list and store new list
		$membersList = $this->removeFromMembersList($groupchat['members'], $uid);
		$adminList = $this->removeFromMembersList($groupchat['owner'], $uid);

		// call the function to update friend's friend list
		if(!$this->updateMembersList($membersList, $adminList, $groupid))
		{
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		/*
			2. Delete user from user's groupchat's list
		*/

		// fetch friend's info
		$user = $this->getUserInfo($uid);

		// call the function to remove user from friend's list and store new list
		$groupchatList = $this->removeFromGroupchatList($user['groupchats'], $groupid);

		// call the function to update friend's friend list
		if(!$this->updateGroupchatList($groupchatList, $uid))
		{
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		/*
			3. Delete any messages made by user
		*/	

		// call the function to delete the conversation between the two users
		if(!$this->deleteConversation($groupid, $uid))
		{
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// if there are no errors from steps 1 to 3
		$alert['message'] = 'You have left '.$groupchat['name'];
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

	// fetch the user's info
	private function getGroupchatInfo($groupid) {

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
		
		// fetch receiver's uid
		if(!$groupchat = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			$alert['message'] = 'Error! User does not exist';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// return receiver's uid
		return $groupchat[0];

	}

	// iterate through member's list and remove the user from it
	private function removeFromMembersList($membersList, $uid) {

		// store member's list of members in array
		$membersList = explode(',', $membersList);

		// iterate through member's list of members
		foreach($membersList as $key => $id)
		{
			// check if any of the member's id's match $uid (member we want to remove)
			if($id == $uid)
			{
				unset($membersList[$key]);
			}
		}

		// take the converted array and convert back to string format separated by comma
		$membersList = implode(',', $membersList);

		// return the members list
		return $membersList;

	}

	// iterate through member's list and remove the user from it
	private function removeFromGroupchatList($groupchatList, $groupid) {

		// store member's list of members in array
		$groupchatList = explode(',', $groupchatList); // ex friend

		// iterate through member's list of members
		foreach($groupchatList as $key => $id)
		{
			// check if any of the member's id's match $uid (member we want to remove)
			if($id == $groupid)
			{
				unset($groupchatList[$key]);
			}
		}

		// take the converted array and convert back to string format separated by comma
		$groupchatList = implode(',', $groupchatList);

		// return the members list
		return $groupchatList;

	}

	// fetch the user's info
	private function updateMembersList($membersList, $ownerList, $groupid) {

		// prepare statement
		$stmt = $this->connect()->prepare('UPDATE groupchat SET members = ?, owner = ? WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($membersList, $ownerList, $groupid))) 
		{
			$stmt = null;
			return false;
		}
		
		// close connection
		$stmt = null;
		
		// return true if friend's list has been updated
		return true;

	}

	// fetch the user's info
	private function updateGroupchatList($groupchatList, $uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('UPDATE users SET groupchats = ? WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($groupchatList, $uid))) 
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
	private function deleteConversation($groupid, $uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('DELETE FROM messages WHERE sender_id = ? AND groupid = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid, $groupid))) 
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