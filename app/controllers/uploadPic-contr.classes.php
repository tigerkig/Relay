<?php

class UploadPicContr extends UploadPic {

	private $file;
	private $fileName;
	private $fileTmpName;
	private $fileSize;
	private $fileError;
	private $fileType;
	private $fileExt;
	private $fileActualExt;
	private $allowed;
	private $uid;
	private $username;


	public function __construct($file, $fileName, $fileTmpName, $fileSize, $fileError, $fileType, $fileExt, $fileActualExt, $allowed, $uid, $username) {
		$this->file = $file;
		$this->fileName = $fileName;
		$this->fileTmpName = $fileTmpName;
		$this->fileSize = $fileSize;
		$this->fileError = $fileError;
		$this->fileType = $fileType;
		$this->fileExt = $fileExt;
		$this->fileActualExt = $fileActualExt;
		$this->allowed = $allowed;
		$this->uid = $uid;
		$this->username = $username;
	}

	public function uploadPicUser() {
		// check if there is a file being uploaded
		if($this->emptyFile() == false) {
			$_SESSION['error'] = 'Error! Your file is empty';
			header('location: ../dashboard.php');
			exit();
		}
		// check if we are logged in
		if($this->emptyUser() == false) {
			$_SESSION['error'] = 'Error! You need to login again';
			header('location: ../dashboard.php');
			exit();
		}
		// check if correct file extension
		if($this->checkFileAllowed() == false) {
			$_SESSION['error'] = 'Error! You cannot upload this type of file';
			header('location: ../dashboard.php');
			exit();
		}
		// check if file does not contain errors
		if($this->checkFileError() == false) {
			$_SESSION['error'] = 'Error! There was an error trying to upload your file';
			header('location: ../dashboard.php');
			exit();
		}
		// check if file size is less than limit
		if($this->checkFileSize() == false) {
			$_SESSION['error'] = 'Error! Your file is too big';
			header('location: ../dashboard.php');
			exit();
		}
		
		$this->setProfilePic($this->fileTmpName, $this->uid, $this->username);
	}

	// check if there is a file being uploaded
	private function emptyFile() {
		$result;
		if(empty($this->file))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if we are logged in
	private function emptyUser() {
		$result;
		if(empty($this->uid) || empty($this->username))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if correct file extension
	private function checkFileAllowed() {
		$result;
		if(!empty($this->file) && !in_array($this->fileActualExt, $this->allowed))
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if file does not contain errors
	private function checkFileError() {
		$result;
		if($this->fileError != 0)
		{
			$result = false;
		}
		else
		{
			$result = true;
		}
		return $result;
	}

	// check if file size is less than limit
	private function checkFileSize() {
		$result;
		if($this->fileSize > 1000000)
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