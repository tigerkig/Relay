<?php
class FetchGroupInfo extends Dbh {

	protected function fetchGroupInfo($groupid) {

		// get group chat's info
		$groupchat = $this->getInfo($groupid, 0);

		// explode members list to array
		$memberList = explode(',',$groupchat['members']);

		// explode admin list
		$adminList = explode(',',$groupchat['owner']);

		// explode admin list
		$banList = explode(',',$groupchat['banlist']);

		// iterate through array
		foreach($memberList as $uid) {

			// get members info
			$member = $this->getInfo($uid, 1);

			$data['currentGroupchat']['members'][$uid]['id'] = $member['id'];
			$data['currentGroupchat']['members'][$uid]['username'] = $member['username'];
			
			// explode members list to array
			$friends = explode(',',$member['friends']);

			// check if the user is a friend, not a friend, or themself
			if(in_array($_SESSION['UID'], $friends)) {
				$isFriend = 0; // chat button
			} else {
				$isFriend = 1; // add friend button
			}
			if($_SESSION['UID'] == $uid) {
				$isFriend = 2; // dont make button as this is the same uid
			}

			// check if admin
			in_array($uid, $adminList) ? $isAdmin = 1 : $isAdmin = 0;
			// check if banned
			in_array($uid, $banList) ? $isBanned = 1 : $isBanned = 0;

			$data['currentGroupchat']['members'][$uid]['isFriend'] = $isFriend;
			$data['currentGroupchat']['members'][$uid]['isAdmin'] = $isAdmin;
			$data['currentGroupchat']['members'][$uid]['isBanned'] = $isBanned;
			$data['currentGroupchat']['members'][$uid]['profilePic'] = $member['profile_pic'];

			// check for friend request if the user sent one to the member
			$sentFriendRequest = $this->getInfo($uid, 2, $_SESSION['UID']);

			$data['currentGroupchat']['members'][$uid]['sentFriendRequest'] = $sentFriendRequest;
		
			// check for friend request if the member sent one to the user
			$receivedFriendRequest = $this->getInfo($_SESSION['UID'], 2, $uid);

			$data['currentGroupchat']['members'][$uid]['receivedFriendRequest'] = $receivedFriendRequest;

		}

		// group chat privacy
		$groupchat['type'] == 0 ? $typeOf = 'private' : $typeOf = 'public';
		
		$data['currentGroupchat']['id'] = $groupchat['id'];
		$data['currentGroupchat']['name'] = $groupchat['name'];
		$data['currentGroupchat']['desc'] = $groupchat['description'];
		$data['currentGroupchat']['type'] = $groupchat['type'];
		$data['currentGroupchat']['typeOf'] = $typeOf;
		$data['currentGroupchat']['timestamp'] = $groupchat['timestamp'];
		$data['currentGroupchat']['adminCount'] = count($adminList);
		$data['currentGroupchat']['memberCount'] = count($memberList);

		// return the data
		echo json_encode($data);
		exit;
	
	}
		


	// fetch user's or group chat's info
	private function getInfo($uid, $type, $member = null) {

		// prepare statement
		if($type == 0) {
			$stmt = $this->connect()->prepare('SELECT * FROM groupchat WHERE id = ?;'); // connect to database

		} elseif($type == 1) {
			$stmt = $this->connect()->prepare('SELECT * FROM users WHERE id = ?;'); // connect to database
		} elseif($type == 2) {
			$stmt = $this->connect()->prepare('SELECT * FROM friend_request WHERE sender_id = ? AND receiver_id = ?;'); // connect to database
		}

		$type == 2 ? $execStatement = array($member, $uid) : $execStatement = array($uid);
		

		// if prepared statement can't connect to the database
		if(!$stmt->execute($execStatement)) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting todd the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// fetch user's info
		if(!$user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;		
		}
		
		// return user's info
		return $user[0];

	}

}