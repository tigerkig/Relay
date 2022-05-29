<?php
class FetchGroupInfoContr extends FetchGroupInfo {

	private $groupid;

	public function __construct($groupid) {
		$this->groupid = $groupid;
	}

	public function fetchGroupInfoUser() {
		// check if receiver username is empty
		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! Please refresh the page';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// if there are no errors -> call function
		$this->fetchGroupInfo($this->groupid);
	}

	// check if uid is empty
	private function emptyUid() {
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

}