<?php
class GetChatProfileStatus extends Dbh {

	protected function getChatProfileStatus($chatUid, $uid, $type) {

		// if a group chat
		if($type == 1) {

			
			// fetch user info and store it in user
			$groupchat = $this->fetchUserInfo($chatUid, $type);

			// explode user's friend's list
			$members = explode(',',$groupchat['members']);

			// check if uid is in the groupchat's member's list
			if(!in_array($uid,$members)) {

				$data = null;

				// close the connection
				$stmt = null;

				// return null data
				echo json_encode($data);
				exit();
			}

			$groupchat['type'] == 1 ? $typeOf = "Public" : $typeOf = "Private";

			// store user's info in data which will be returned
			$data['profile']['id'] = $groupchat['id'];
			$data['profile']['name'] = $groupchat['name'];
			$data['profile']['memberCount'] = count($members);
			$data['profile']['members'] = $groupchat['members'];
			$data['profile']['owner'] = $groupchat['owner'];
			$data['profile']['type'] = $type;
			$data['profile']['typeOf'] = $typeOf;
			$data['profile']['desc'] = $groupchat['description'];
		}
		
		// if not a group chat
		if($type == 0) {

			// fetch user info and store it in user
			$user = $this->fetchUserInfo($chatUid, $type);

			// explode user's friend's list
			$friends = explode(',',$user['friends']);

			// check if uid is in the user's friend's list
			if(!in_array($uid,$friends)) {

				$data = null;

				// close the connection
				$stmt = null;

				// return null data
				echo json_encode($data);
				exit();
			}

			// if current time is less than user's last active timestamp + 60 set to active
			if(time() < $user['last_active']+60) {
				$activeNow = ' (active now)';
				$statusColor = 'green';
			}

			// if current time is greater than or equal to user's last active timestamp + 60 set to away
			if(time() >= $user['last_active']+60) {
				$activeNow = ' (away)';
				$statusColor = 'orange';
			}

			// if current time is greater than to user's last active timestamp + 120 set to offline
			if(time() > $user['last_active']+120) {
				$activeNow = ' (away)';
				$statusColor = '#eee';
			}

			// store user's info in data which will be returned
			$data['profile']['id'] = $chatUid;
			$data['profile']['username'] = $user['username'];
			$data['profile']['email'] = $user['email'];
			$data['profile']['last_active'] = $user['last_active'];
			$data['profile']['online_status'] = $user['online_status'];
			$data['profile']['status_color'] = $statusColor;
			$data['profile']['active_now'] = $activeNow;
			$data['profile']['type'] = $type;
			
			if($user['profile_pic'] == 1) {
				$data['profile']['profilePic'] = $chatUid.'_'.$user['username'].'.jpg';
			} else {
				$data['profile']['profilePic'] = 'no_pic.jpg';
			}
			
			// close the connection
			$stmt = null;
		}

		// return user's info thats store in data
		echo json_encode($data);
		exit();
	
	}

	// fetch chatUid's friends list, last_active, online_status, and email
	private function fetchUserInfo($uid, $type) {

		// prepare statement -> grab user info or groupchat info
		if($type == 0) {
			$stmt = $this->connect()->prepare('SELECT * FROM users WHERE id = ?;'); // connect to database
		}

		if($type == 1) {
			$stmt = $this->connect()->prepare('SELECT * FROM groupchat WHERE id = ?;'); // connect to database
		}

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's info
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// return users info
		return $user[0];

	}

}