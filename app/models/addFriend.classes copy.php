<?php
class CancelFriendRequest extends Dbh {

	protected function cancelFriendRequest($uid, $receiver) {

		// fetch requests
		$friendRequests = $this->getInfo($uid, $receiver);

	
	}
		


	// fetch the receiver's uid
	private function getInfo($uid, $receiver) {

		// prepare statement
		$stmt = $this->connect()->prepare('DELETE * FROM friend_request WHERE sender_id = ? AND id =? ;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid,$receiver))) 
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

}