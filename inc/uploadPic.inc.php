<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	// Build POST request
    require '../classes/recaptcha.php';
	
	// Verified

	// Grab post data
	$file = $_FILES['file'];
	$fileName = $_FILES['file']['name'];          
	$fileTmpName = $_FILES['file']['tmp_name'];
	$fileSize = $_FILES['file']['size'];
	$fileError = $_FILES['file']['error'];
	$fileType = $_FILES['file']['type'];
	$fileExt = explode('.', $fileName);
	$fileActualExt = strtolower(end($fileExt));
	$allowed = array('jpg', 'jpeg', 'png');
	$uid = $_SESSION['UID'];
	$username = $_SESSION['USERNAME'];

	// Instantiate Login controller class
	include '../app/core/dbh.classes.php';
	include '../app/models/uploadPic.classes.php';
	include '../app/controllers/uploadPic-contr.classes.php';
	
	$uploadPic = new UploadPicContr($file, $fileName, $fileTmpName, $fileSize, $fileError, $fileType, $fileExt, $fileActualExt, $allowed, $uid, $username); // create new object

	// Running error handlers and user registration
	$uploadPic->uploadPicUser();

	header('Location: ../dashboard.php');

} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../dashboard.php");
    die();
}