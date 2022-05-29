<?php
class AddFriendContr extends AddFriend {

	private $receiver;
	private $sender;
	private $senderUid;

	public function __construct($receiver, $sender, $senderUid) {
		$this->receiver = $receiver;
		$this->sender = $sender;
		$this->senderUid = $senderUid;
	}

	public function addFriendUser() {
		// check if receiver username is empty
		if($this->emptyUsername() == false) {
			$alert['message'] = 'Error! Please enter a username';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// check if user is trying to add themselves as a friend
		if($this->checkReceiver() == false) {
			$alert['message'] = 'Error! You cannot add yourself as a friend';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call addFriend function
		$this->addFriend($this->receiver, $this->sender, $this->senderUid);
	}

	// check if receiver username is empty
	private function emptyUsername() {
		$result;
		if(empty($this->receiver))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if user is trying to add themselves as a friend
	private function checkReceiver() {
		$result;
		if($this->receiver == $this->sender)
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