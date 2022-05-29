<?php
class GetChatProfileStatusContr extends GetChatProfileStatus {

	private $chatUid;
	private $uid;
	private $type;

	public function __construct($chatUid, $uid, $type) {
		$this->chatUid = $chatUid;
		$this->uid = $uid;
		$this->type = $type;
	}

	public function getChatProfileStatusUser() {
		// check if the chatUid (who the user is chatting with)
		if($this->emptyChatUid() == false) {
			$alert['message'] = 'Error! Please refresh the page';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// check if user's session UID is not set
		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! You need to log in again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call getChatProfileStatus function
		$this->getChatProfileStatus($this->chatUid, $this->uid, $this->type);
	}

	// check if receiver username is empty
	private function emptyChatUid() {
		$result;
		if(empty($this->chatUid))
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
	private function emptyUid() {
		$result;
		if(empty($this->chatUid))
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