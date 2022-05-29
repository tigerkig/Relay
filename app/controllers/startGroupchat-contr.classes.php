<?php
class StartGroupchatContr extends StartGroupchat {

	private $uid;
    private $name;
	private $desc;
	private $type;

	public function __construct($uid, $name, $desc, $type) {
		$this->uid = $uid;
        $this->name = $name;
		$this->desc = $desc;
		$this->type = $type;
	}

	public function startGroupchatUser() {

		if($this->emptyUid() == false) {
			$alert['message'] = 'Error! You need to log in again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		if($this->emptyInput() == false) {
			$alert['message'] = 'Please enter a group chat name and description';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		if($this->wrongType() == false) {
			$alert['message'] = 'Error! Please try again';
        	$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		$this->startGroupchat($this->uid, $this->name, $this->desc, $this->type);
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

	// check if group chat name is empty
	private function emptyInput() {
		$result;
		if(empty($this->name) || empty($this->desc))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if type is 1 or 2
	private function wrongType() {
		$result;
		if(!is_numeric($this->type) && ($this->type !== '1' || $this->type !== '2'))
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