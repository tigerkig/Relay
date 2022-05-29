<?php
class UpdateGroupchatSettingsContr extends UpdateGroupchatSettings {

	private $uid;
    private $updateGroupchatObj;
	private $profanity;

	public function __construct($uid, $updateGroupchatObj, $profanity) {
		$this->uid = $uid;
        $this->updateGroupchatObj = $updateGroupchatObj;
		$this->profanity = $profanity;
	}

	public function updateGroupchatSettingsUser() {
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
		// check if name, desc, privacy, id is empty
		if($this->emptyObj() == false) {
			$alert['message'] = "Error! Group chat name and description cannot be empty";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		// check if group name contains profanity
		if($this->containsProfanity() == false) {
			$alert['message'] = "Error! Group chat name and description must not contain profanity";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}
		// check if name and desc is alpha numeric
		if($this->alphaNumeric() == false) {
			$alert['message'] = "Error! Group chat name and description contain only letters and numbers";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// check if group name is min 3 chars and less than 30 characters
		if($this->nameLength() == false) {
			$alert['message'] = "Error! Group chat name must be atleast 3 characters and no more than 30 characters long";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// check if group description is min 3 chars and less than 60 characters
		if($this->descLength() == false) {
			$alert['message'] = "Error! Group chat description must be atleast 3 characters and no more than 60 characters long";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// check if privacy setting is not 0 or 1
		if($this->privacySetting() == false) {
			$alert['message'] = "Error! Cannot update group settings at this time, try refreshing the page";
			$alert['type'] = 'error';
			echo json_encode($alert);
			exit();
		}

		// use trim on name and desc in model
		$this->updateGroupchatSettings($this->uid, $this->updateGroupchatObj);
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
		foreach ($this->updateGroupchatObj as $key => $val) {
			if(empty($this->updateGroupchatObj[$key]) && !is_numeric($this->updateGroupchatObj[$key]))
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
		if(!$this->checkUser($this->uid, $this->updateGroupchatObj['id']))
		{
			$result = false;
		} 
		else
		{
			$result = true;
		}
		return $result;
	}

	// Check if uid contains profanity
	private function containsProfanity() {
		$result;
		foreach($this->profanity as $word)
		{
			if((strpos($this->updateGroupchatObj['name'],$word) !== false) || (strpos($this->updateGroupchatObj['desc'],$word) !== false))
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

	// check if group name and description is alpha numeric
	private function alphaNumeric() {
		$result;
		$regex = '/^[a-zA-Z0-9 ]*$/';
		if(!preg_match($regex,$this->updateGroupchatObj['name']) || !preg_match($regex,$this->updateGroupchatObj['desc']))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if group name is min 3 chars and less than 30 characters
	private function nameLength() {
		$result;
		if(strlen($this->updateGroupchatObj['name']) < 3 || strlen($this->updateGroupchatObj['name']) > 30)
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if group desc is min 3 chars and less than 60 characters
	private function descLength() {
		$result;
		if(strlen($this->updateGroupchatObj['desc']) < 3 || strlen($this->updateGroupchatObj['desc']) > 60)
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if privacy setting is not 0 or 1
	private function privacySetting() {
		$result;
		if(strpos($this->updateGroupchatObj['privacy'], '0') === false && strpos($this->updateGroupchatObj['privacy'], '1') === false)
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