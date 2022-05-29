<?php
class SendMessageContr extends SendMessage {

	private $msg;
	private $senderId;
	private $receiverId;
	private $type;
	private $groupid;

	public function __construct($msg, $senderId, $receiverId, $type, $groupid) {
		$this->msg = $msg;
		$this->senderId = $senderId;
		$this->receiverId = $receiverId;
		$this->type = $type;
		$this->groupid = $groupid;
	}

	public function sendMessageUser() {
		// check if message is empty
		if($this->emptyInput() == false) {
			$alert['message'] = 'Error! Message must not be empty';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		// check if senderId or receiverId is empty
		if($this->emptyIds() == false) {
			$alert['message'] = 'Error! Please refresh the chat page and try again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call insertMessage function
		$this->insertMessage($this->msg, $this->senderId, $this->receiverId, $this->type, $this->groupid);
	}

	private function emptyInput() {
		$result;
		if(empty($this->msg))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	private function emptyIds() {
		$result;
		if(empty($this->senderId) || empty($this->receiverId))
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