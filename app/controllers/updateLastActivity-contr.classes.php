<?php
class UpdateLastActivityContr extends UpdateLastActivity {

	private $uid;

	public function __construct($uid) {
		$this->uid = $uid;
	}

	public function updateLastActivityUser() {
		// check if message is empty
		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! UID is empty';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call insertMessage function
		$this->updateLastActivity($this->uid);
	}

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