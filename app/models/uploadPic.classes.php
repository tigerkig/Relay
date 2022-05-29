<?php

class UploadPic extends Dbh {

	protected function setProfilePic($fileTmpName, $uid, $username) { // insert new profile pic to uplaods

		// set profile pic status to 1 (true)
		$profilePicStatus = 1;

		// set a name to the file being uploaded
		$fileNameNew = $uid.'_'.$username.'.jpg';

		// push file to uploads folder
		move_uploaded_file($fileTmpName, '../uploads/'.$fileNameNew);

		// update user's profile pic status
		$stmt = $this->connect()->prepare('UPDATE users SET profile_pic = ? WHERE id = ?;'); // connect to database
		if(!$stmt->execute(array($profilePicStatus, $uid))) {
			$stmt = null;
			$_SESSION['error'] = 'Error! Connection to database failed';
			header('location: ../dashboard.php');
			exit();
		}

		// close connection
		$stmt = null;

		// create success message
		$_SESSION['success'] = 'Success! You changed your profile picture';
		
	}
	
}