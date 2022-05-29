<?php
class FriendRequestContr extends FriendRequest {

	private $response;
	private $senderUid;
	private $uid;

	public function __construct($response, $senderUid, $uid) {
		$this->response = $response;
		$this->senderUid = $senderUid;
		$this->uid = $uid;
	}

	public function friendRequestUser() {
		// check if data is empty
		if($this->emptyData() == false) {
			$alert['message'] = 'Error! Please try again later';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call friend request function
		$this->friendRequest($this->response, $this->senderUid, $this->uid);

	}

	// check if any of the posted data is empty
	private function emptyData() {
		$result;
		if(empty($this->response) || empty($this->senderUid) || empty($this->uid))
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