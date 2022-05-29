<?php
class CancelFriendRequest extends Dbh {

	protected function cancelFriendRequest($uid, $receiver) {
		/*
			1. Cancel ongoing friend request
		*/
		$_SESSION['todelete1'] = array($uid,$receiver);
		

		//fetch friend's info
		$user = $this->getUserInfo($receiver);
		
		// call the function to update friend's friend list
		if(!$this->deleteInvite($uid, $receiver))
		{
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		$_SESSION['todelete2'] = array($uid,$receiver);

		// if there are no errors from steps 1 to 3
		$alert['message'] = 'You have cancelled your friend invite for '.$user['username'];
        $alert['type'] = 'success';

		// send back a success alert
		echo json_encode($alert);
		exit();

	}

	// fetch the user's info
	private function getUserInfo($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT username FROM users WHERE id = ?;'); // connect to database

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
	private function deleteInvite($uid,$member) {

		// prepare statement
		$stmt = $this->connect()->prepare('DELETE FROM friend_request WHERE sender_id = ? AND receiver_id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		$_SESSION['todelete'] = array($uid,$member);
		if(!$stmt->execute(array($uid,$member))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// fetch an invite and if it 
		if(!$invite = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return true;
		}

		return true;

	}

}