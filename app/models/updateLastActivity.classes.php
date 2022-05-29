<?php
class UpdateLastActivity extends Dbh {

	protected function updateLastActivity($uid) { // login the user

		// new last activity timestamp
		$timestamp = time();

		// prepare statement to check if already existing friend request between receiver and sender
		$stmt = $this->connect()->prepare('UPDATE users SET last_active = ? WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($timestamp, $uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
			$alert['type'] = 'success';
			echo json_encode($alert);
			exit();
		}

		// close connection
		$stmt = null;

		exit();
	
	}
	
}