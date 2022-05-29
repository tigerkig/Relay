<?php
class LeaveGroupchatContr extends LeaveGroupchat {

	private $groupid;
	private $uid;

	public function __construct($groupid, $uid) {
		$this->groupid = $groupid;
		$this->uid = $uid;
	}

	public function leaveGroupchatUser() {

		// check if friend's uid is empty
		if($this->emptyGroupid() == false) {
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
		
		// if there are no errors -> call leave group function
		$this->leaveGroupchat($this->groupid, $this->uid);
	}

	// check if friend's uid is empty
	private function emptyGroupid() {
		$result;
		if(empty($this->groupid))
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