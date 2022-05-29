<?php
class ChangeReadStatusContr extends ChangeReadStatus {

	private $uid;
	private $chatUid;

	public function __construct($uid, $chatUid) {
		$this->uid = $uid;
		$this->chatUid = $chatUid;
	}

	public function changeReadStatusUser() {

		// check if uid or chatUid is empty
		if($this->emptyUids() == false) {
			$alert['message'] = 'Error! Please refresh the chat and try again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call changeReadStatus function
		$this->changeReadStatus($this->uid, $this->chatUid);
	}

	// check if uid or chatUid is empty
	private function emptyUids() {
		$result;
		if(empty($this->uid) || empty($this->chatUid))
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