<?php
class UpdateGroupchatSettings extends Dbh {

	protected function updateGroupchatSettings($uid, $updateGroupchatObj) { // update user's email

		// only update if any settings have changed
		$stmt = $this->connect()->prepare('UPDATE groupchat SET name = ?, type = ?, description = ? WHERE id = ? AND ( name <> ? OR	type <> ? OR description <> ? );'); // connect to database

		if(!$stmt->execute(array($updateGroupchatObj['name'],$updateGroupchatObj['privacy'],$updateGroupchatObj['desc'], $updateGroupchatObj['id'], $updateGroupchatObj['name'],$updateGroupchatObj['privacy'],$updateGroupchatObj['desc']))) 
		{
			$stmt = null;
			$alert['message'] = 'Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// close connection
		$stmt = null;

		$alert['message'] = 'Success! '.$updateGroupchatObj['name'].' has been updated';
		$alert['type'] = 'success';
		echo json_encode($alert);
		exit();
	}

	protected function checkUser($uid, $groupid) { // check if id and password match

		$stmt = $this->connect()->prepare('SELECT owner FROM groupchat WHERE id = ?;'); // connect to database

		if(!$stmt->execute(array($groupid))) {
			$stmt = null;
			$alert['message'] = 'Error! Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		if($stmt->rowCount() == 0) // check if username or email exists
		{	
			$stmt = null;
			$alert['message'] = 'Error! This group chat does not exist';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch list of admins
		$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// explode admin list
		$admins = explode(',',$admins[0]['owner']);

		$resultCheck;
		// check if user id is admin
		if(!in_array($uid,$admins))
		{
			$resultCheck = false;
		}
		else
		{
			$resultCheck = true;
		}

		return $resultCheck;

	}
	
}