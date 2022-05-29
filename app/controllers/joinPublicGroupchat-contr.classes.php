<?php
class JoinPublicGroupchatContr extends JoinPublicGroupchat {

	private $uid;
    private $groupid;

	public function __construct($uid, $groupid) {
		$this->uid = $uid;
        $this->groupid = $groupid;
	}

	public function joinPublicGroupchatUser() {

		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! You need to log in again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		$this->joinPublicGroupchat($this->uid, $this->groupid);
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

}