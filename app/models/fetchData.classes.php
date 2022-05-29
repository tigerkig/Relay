<?php
class FetchData extends Dbh {

	protected function fetchData($uid) {

		// get user's info
		$user = $this->getUserInfo($uid);

		// set activity status for user
		$lastActive = $user['last_active'];
		if(time() < $lastActive+60) {
			$statusColor = 'green';
		} elseif(time() >= $lastActive+60 && time() <= $lastActive+120) {
			$statusColor= 'orange';
		} elseif(time() > $lastActive+120) {
			$statusColor = '#eee';
		}

		// destructure user's friends to array
		$friends = explode(',', $user['friends']);

		// destructure user's groupchats to array
		$groupchats = explode(',', $user['groupchats']);

		// set user's info to data variable to return
		$data['account']['id'] = $user['id'];
		$data['account']['username'] = $user['username'];
		$data['account']['email'] = $user['email'];
		$data['account']['last_active'] = $user['last_active'];
		$data['account']['online_status'] = $user['online_status'];
		$data['account']['status_color'] = $statusColor;
		
		if($user['profile_pic'] == 1) {
		    $data['account']['profilePic'] = $user['id'].'_'.$user['username'].'.jpg';
		} else {
		    $data['account']['profilePic'] = 'no_pic.jpg';
		}

		// set session account to data['account']
		$_SESSION['account'] = $data['account'];

		// set user's info to session variables (backup)
		$_SESSION['EMAIL'] = $user['email'];
		$_SESSION['first_login'] = $user['first_login'];
		$_SESSION['USERNAME'] = $user['username'];
		$_SESSION['last_active'] = $user['last_active'];
		$_SESSION['online_status'] = $user['online_status'];
		$_SESSION['status_color'] = $statusColor;
		$_SESSION['security'] = $user['2fa'];
		$_SESSION['notify'] = $user['notify'];

		// remove any null friend's on list
		for($i = 0 ; $i <= count($friends) ; $i++) {
			if(!$friends[$i]) {
				unset($friends[$i]);
			}
		}		

		// iterate through user's friend list to set data to be returned
		foreach($friends as $friend) {

			// get friend's info
			$friendInfo = $this->getUserInfo($friend);

			// get messages between user and friend
			$messages = $this->getMessages($uid, $friend);

			// check if the message is unread or not
			foreach($messages as $message) {
				
				if($message['status'] == 0) {
					$numUnread++;
					$friendUsername = '<strong>'.$friendInfo['username'].'</strong> <span class="msgCount">'.$numUnread.'</span>';
					$_SESSION['friendusernmae'] = $friendInfo['username'];
				} else {
					$friendUsername = $friendInfo['username'];
					$_SESSION['friendusernmae'] = $friendInfo['username'];

				}

			}

			// if the user has not sent a message to a friend
			if($messages == null) {
				$friendUsername = $friendInfo['username'];
			}

			// set activity status for friend
			$lastActive = $friendInfo['last_active'];
			if(time() < $lastActive+60) {
				$statusColor = 'green';
				$activeNow = ' (active now)';
			} elseif(time() >= $lastActive+60 && time() <= $lastActive+120) {
				$statusColor= 'orange';
				$activeNow = ' (away)';
			} elseif(time() > $lastActive+120) {
				$statusColor = '#eee';
				$activeNow = ' (offline)';
			}

			// set friend's info to data variable to return
			$data['friends'][$friend]['id'] = $friendInfo['id'];
			$data['friends'][$friend]['username'] = $friendUsername;
			$data['friends'][$friend]['last_active'] = $friendInfo['last_active'];
			$data['friends'][$friend]['online_status'] = $friendInfo['online_status'];
			$data['friends'][$friend]['active_now'] = $activeNow;
			$data['friends'][$friend]['status_color'] = $statusColor;
			$data['friends'][$friend]['type'] = 0;
			
			if($friendInfo['profile_pic'] == 1) {
    		    $data['friends'][$friend]['profilePic'] = $friendInfo['id'].'_'.$friendInfo['username'].'.jpg';
    		} else {
    		    $data['friends'][$friend]['profilePic'] = 'no_pic.jpg';
    		}
		
		}

		// if online status is set to off then move to bottom of online friend's list
		usort($data['friends'], function($a, $b) {
			return $b['online_status'] <=> $a['online_status'];
		});

		// sort friend's list by last active time
		usort($data['friends'], function($a, $b) {
			return $b['last_active'] <=> $a['last_active'];
		});

		// fetch incoming friend requests
		$friendRequests = $this->getFriendRequests($uid);

		// iterate through each incoming friend request
		foreach($friendRequests as $friendRequest) {

			// get friend info for the friend request
			$friend = $this->getUserInfo($friendRequest['sender_id']);

			$data['requests'][$friendRequest['id']]['id'] = $friendRequest['id'];
			$data['requests'][$friendRequest['id']]['username'] = $friend['username'];
			$data['requests'][$friendRequest['id']]['sender_id'] = $friendRequest['sender_id'];
			$data['requests'][$friendRequest['id']]['receiver_id'] = $friendRequest['receiver_id'];
			$_SESSION['requests'] = $data['requests'];
		} 

		// remove any null friend's on list
		for($i = 0 ; $i <= count($friends) ; $i++) {
			if(!$friends[$i]) {
				unset($friends[$i]);
			}
		}	

		// iterate through user's groupchat list to set data to be returned
		foreach($groupchats as $groupchat) {

			// get groupchat's info
			$groupchatInfo = $this->getGroupchatInfo($groupchat);
			
			if($groupchat) {
				// set groupchats's info to data variable to return
				$data['groupchats'][$groupchat]['id'] = $groupchatInfo['id'];
				$data['groupchats'][$groupchat]['name'] = $groupchatInfo['name'];
				$data['groupchats'][$groupchat]['members'] = $groupchatInfo['members'];
				// 1 represents group chat, this will be used to determine if the chat session is a group or not
				$data['groupchats'][$groupchat]['type'] = 1;
			}
		
		}

		

		$publicGroupchats = $this->getPublicGroupchats();

		// remove any null friend's on list
		for($i = 0 ; $i <= count($publicGroupchats) ; $i++) {
			if(!$publicGroupchats[$i]) {
				unset($publicGroupchats[$i]);
			}
		}	

		foreach($publicGroupchats as $publicGroupchat) {

			// count group members
			$memberCount = explode(',',$publicGroupchat['members']);
			$memberCount = count($memberCount);
			
			$data['publicGroupchats'][$publicGroupchat['id']]['id'] = $publicGroupchat['id'];
			$data['publicGroupchats'][$publicGroupchat['id']]['name'] = $publicGroupchat['name'];
			$data['publicGroupchats'][$publicGroupchat['id']]['desc'] = $publicGroupchat['description'];
			$data['publicGroupchats'][$publicGroupchat['id']]['members'] = $publicGroupchat['members'];
			$data['publicGroupchats'][$publicGroupchat['id']]['memberCount'] = $memberCount;


		}
		
		// return the data
		echo json_encode($data);
		exit;
	
	}
		


	// fetch user's info
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
		
		// fetch user's info
		if(!$user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;		
		}
		
		// return user's info
		return $user[0];

	}

	// fetch sender's friend list
	private function getMessages($uid, $friendUid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM messages WHERE receiver_id=? AND sender_id=?'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid, $friendUid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's messages
		if(!$messages = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;	
		}

		// return user's friend's list	
		return $messages;

	}

	// fetch incoming friend requests for user
	private function getFriendRequests($uid) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM friend_request WHERE receiver_id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($uid))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// fetch user's messages
		if(!$friendRequests = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;	
		}

		// return user's friend's list	
		return $friendRequests;

	}

	// fetch groupchats's info
	private function getGroupchatInfo($id) {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM groupchat WHERE id = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array($id))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// fetch user's info
		if(!$groupchat = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;		
		}
		
		// return user's info
		return $groupchat[0];

	}

	// fetch all public group chats
	private function getPublicGroupchats() {

		// prepare statement
		$stmt = $this->connect()->prepare('SELECT * FROM groupchat WHERE type = ?;'); // connect to database

		// if prepared statement can't connect to the database
		if(!$stmt->execute(array(1))) 
		{
			$stmt = null;
			$alert['message'] = 'Error! The server is having trouble connecting to the database';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// fetch user's info
		if(!$groupchats = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			return null;		
		}

		// return user's info
		return $groupchats;

	}

}