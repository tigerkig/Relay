<?php
class FetchDataContr extends FetchData {

	private $uid;

	public function __construct($uid) {
		$this->uid = $uid;
	}

	public function fetchDataUser() {
		// check if receiver username is empty
		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! Please login again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		
		// if there are no errors -> call fetchData function
		$this->fetchData($this->uid);
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