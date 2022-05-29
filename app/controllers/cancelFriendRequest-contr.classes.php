<?php
class CancelFriendRequestContr extends CancelFriendRequest {

	private $uid;
	private $receiver;

	public function __construct($uid, $receiver) {
		$this->uid = $uid;
		$this->receiver = $receiver;
	}

	public function cancelFriendRequestUser() {
		$_SESSION['todelete'] = array($this->uid, $this->receiver);
		// check if receiver username is empty
		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! You need to refresh the page!';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call addFriend function
		$this->cancelFriendRequest($this->uid, $this->receiver);
	}

	// check if receiver username is empty
	private function emptyUid() {
		$result;
		if(empty($this->receiver) || empty($this->uid))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

}