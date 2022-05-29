<?php
class GetChatMessagesContr extends GetChatMessages {

	private $senderId;
	private $receiverId;
	private $username;
	private $type;

	public function __construct($senderId, $receiverId, $username, $type) {
		$this->senderId = $senderId;
		$this->receiverId = $receiverId;
		$this->username = $username;
		$this->type = $type;
	}

	public function getChatMessagesUser() {
		// check if senderId, receiverId or username is empty
		if($this->emptyIds() == false) {
			$alert['message'] = 'Error! Please refresh the chat page and try again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call insertMessage function
		$this->getChatMessages($this->senderId, $this->receiverId, $this->username, $this->type);
	}

	private function emptyIds() {
		$result;
		if(empty($this->senderId) || empty($this->receiverId) || empty($this->username))
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