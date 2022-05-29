<?php
class ChangeReadStatus extends Dbh {

	// change scripts.php to catch and display $alert

	protected function changeReadStatus($uid, $chatUid) {
		
		// prepare statement to check if already existing friend request between receiver and sender
		$stmt = $this->connect()->prepare('UPDATE messages SET status = 1 WHERE receiver_id = ? AND sender_id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid, $chatUid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// close the connection
		$stmt = null;

		exit();
	
	}

}