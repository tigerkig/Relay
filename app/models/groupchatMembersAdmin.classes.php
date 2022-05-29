<?php
class GroupchatMembersAdmin extends Dbh {

	protected function groupchatMembersAdmin($uid, $obj) { // update user's email

		switch ($obj['action']) {
			case "promote":

				$stmt = $this->connect()->prepare('SELECT owner FROM groupchat WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($obj['groupid']))) {
					$stmt = null;
					$alert['message'] = 'Error! Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// fetch list of admins
				$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

				// explode admin list
				$admins = explode(',',$admins[0]['owner']);

				array_push($admins,$obj['uid']);

				$admins = implode(',',$admins);

				$stmt = $this->connect()->prepare('UPDATE groupchat SET owner = ? WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($admins,$obj['groupid']))) 
				{
					$stmt = null;
					$alert['message'] = 'Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// close connection
				$stmt = null;

				$alert['message'] = 'Success! User has been promoted';
				$alert['type'] = 'success';
				echo json_encode($alert);
				exit();

				break;
			case "demote":

				$stmt = $this->connect()->prepare('SELECT owner FROM groupchat WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($obj['groupid']))) {
					$stmt = null;
					$alert['message'] = 'Error! Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// fetch list of admins
				$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

				// explode admin list
				$admins = explode(',',$admins[0]['owner']);

				foreach($admins as $key => $id)
				{
					if($id == $obj['uid'])
					{
						unset($admins[$key]);
					}
				}

				$admins = implode(',',$admins);

				$stmt = $this->connect()->prepare('UPDATE groupchat SET owner = ? WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($admins,$obj['groupid']))) 
				{
					$stmt = null;
					$alert['message'] = 'Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// close connection
				$stmt = null;

				$alert['message'] = 'Success! User has been demoted';
				$alert['type'] = 'success';
				echo json_encode($alert);
				exit();
				
				break;
			case "ban":
				$stmt = $this->connect()->prepare('SELECT banlist FROM groupchat WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($obj['groupid']))) {
					$stmt = null;
					$alert['message'] = 'Error! Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// fetch list of admins
				$banlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

				// explode admin list
				$banlist = explode(',',$banlist[0]['banlist']);

				array_push($banlist,$obj['uid']);

				$banlist = implode(',',$banlist);

				$stmt = $this->connect()->prepare('UPDATE groupchat SET banlist = ? WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($banlist,$obj['groupid']))) 
				{
					$stmt = null;
					$alert['message'] = 'Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// close connection
				$stmt = null;

				$alert['message'] = 'Success! User has been banned';
				$alert['type'] = 'success';
				echo json_encode($alert);
				exit();
				break;
			case "unban":
				$stmt = $this->connect()->prepare('SELECT banlist FROM groupchat WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($obj['groupid']))) {
					$stmt = null;
					$alert['message'] = 'Error! Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// fetch list of admins
				$banlist = $stmt->fetchAll(PDO::FETCH_ASSOC);

				// explode admin list
				$banlist = explode(',',$banlist[0]['banlist']);

				foreach($banlist as $key => $id)
				{
					if($id == $obj['uid'])
					{
						unset($banlist[$key]);
					}
				}

				$banlist = implode(',',$banlist);

				$stmt = $this->connect()->prepare('UPDATE groupchat SET banlist = ? WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($banlist,$obj['groupid']))) 
				{
					$stmt = null;
					$alert['message'] = 'Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// close connection
				$stmt = null;

				$alert['message'] = 'Success! User has been unbanned';
				$alert['type'] = 'success';
				echo json_encode($alert);
				exit();
				break;
			case "kick":
				// if the user is an admin, remove them
				$stmt = $this->connect()->prepare('SELECT owner FROM groupchat WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($obj['groupid']))) {
					$stmt = null;
					$alert['message'] = 'Error! Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$admins = explode(',',$admins[0]['owner']);

				foreach($admins as $key => $id)
				{
					if($id == $obj['uid'])
					{
						unset($admins[$key]);
					}
				}

				$admins = implode(',',$admins);

				$stmt = $this->connect()->prepare('UPDATE groupchat SET owner = ? WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($admins,$obj['groupid']))) 
				{
					$stmt = null;
					$alert['message'] = 'Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// close connection
				$stmt = null;

				// update members list remove user
				$stmt = $this->connect()->prepare('SELECT members FROM groupchat WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($obj['groupid']))) {
					$stmt = null;
					$alert['message'] = 'Error! Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$members = explode(',',$members[0]['members']);

				foreach($members as $key => $id)
				{
					if($id == $obj['uid'])
					{
						unset($members[$key]);
					}
				}

				$members = implode(',',$members);

				$stmt = $this->connect()->prepare('UPDATE groupchat SET members = ? WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($members,$obj['groupid']))) 
				{
					$stmt = null;
					$alert['message'] = 'Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// close connection
				$stmt = null;

				// update users groupchat list and remove groupchat
				$stmt = $this->connect()->prepare('SELECT groupchats FROM users WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($obj['uid']))) {
					$stmt = null;
					$alert['message'] = 'Error! Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				$groupchats = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$groupchats = explode(',',$groupchats[0]['groupchats']);

				foreach($groupchats as $key => $id)
				{
					if($id == $obj['groupid'])
					{
						unset($groupchats[$key]);
					}
				}

				$groupchats = implode(',',$groupchats);

				$stmt = $this->connect()->prepare('UPDATE users SET groupchats = ? WHERE id = ?;'); // connect to database

				if(!$stmt->execute(array($groupchats,$obj['uid']))) 
				{
					$stmt = null;
					$alert['message'] = 'Connection to database failed';
					$alert['type'] = 'error';
					echo json_encode($alert);
					exit();
				}

				// close connection
				$stmt = null;

				$alert['message'] = 'Success! User has been kicked from the group chat';
				$alert['type'] = 'success';
				echo json_encode($alert);
				exit();
				break;
			default:
				$alert['message'] = 'Error! There is no action detected';
				$alert['type'] = 'error';
				echo json_encode($alert);
				exit();
		}

	}

	protected function checkUser($uid, $groupid) {

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

	protected function checkMember($uid, $groupid) { // check if id and password match

		$stmt = $this->connect()->prepare('SELECT members FROM groupchat WHERE id = ?;'); // connect to database

		if(!$stmt->execute(array($groupid))) {
			$stmt = null;
			$alert['message'] = 'Error! Connection to database failed';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		if($stmt->rowCount() == 0)
		{	
			$stmt = null;
			$alert['message'] = 'Error! This group chat does not exist';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch list of admins
		$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// explode admin list
		$members = explode(',',$members[0]['members']);

		$resultCheck;
		// check if user id is admin
		if(!in_array($uid,$members))
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