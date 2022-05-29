<?php
class RemoveFriendContr extends RemoveFriend {

	private $friendUid;
	private $uid;

	public function __construct($friendUid, $uid) {
		$this->friendUid = $friendUid;
		$this->uid = $uid;
	}

	public function removeFriendUser() {

		// check if friend's uid is empty
		if($this->emptyFriendUid() == false) {
			$alert['message'] = 'Error! Please refresh the page';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// check if uid is empty
		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! Please login again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call removeFriend function
		$this->removeFriend($this->friendUid, $this->uid);
	}

	// check if friend's uid is empty
	private function emptyFriendUid() {
		$result;
		if(empty($this->friendUid))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if uid is empty
	private function emptyUid() {
		$result;
		if(empty($this->uid))
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