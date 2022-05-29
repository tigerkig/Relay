<?php
class GroupchatMembersAdminContr extends GroupchatMembersAdmin {

	private $uid;
    private $obj;

	public function __construct($uid, $obj) {
		$this->uid = $uid;
        $this->obj = $obj;
	}

	public function groupchatMembersAdminUser() {
		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! You need to login again';
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		// check if user is an admin
		if($this->isAdmin() == false) {
			$alert['message'] = "Error! You don't have permission for this";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		// check if user is in the group chat
		if($this->isMember() == false) {
			$alert['message'] = "Error! Member is not in the group chat";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		// check if name, desc, privacy, id is empty
		if($this->emptyObj() == false) {
			$alert['message'] = "Error! Please refresh the page";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// use trim on name and desc in model
		$this->groupchatMembersAdmin($this->uid, $this->obj);
	}

	// check if uid isn't set
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

	// check if obj is empty
	private function emptyObj() {
		$result;
		foreach ($this->obj as $key => $val) {
			if(empty($this->obj[$key]) && !is_numeric($this->obj[$key]))
			{
				$result = false;
				break;
			}
			else
			{
				$result = true;
			}

		}
		return $result;
	}

	// check if user is an admin
	private function isAdmin() {
		$result;
		if(!$this->checkUser($this->uid, $this->obj['groupid']))
		{
			$result = false;
		} 
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if user is an admin
	private function isMember() {
		$result;
		if(!$this->checkMember($this->obj['uid'], $this->obj['groupid']))
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