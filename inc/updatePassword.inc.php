<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	
	// Grab post data
	$uid = $_SESSION['UID'];
	$pwd = $_POST['pwd'];
	$repeatPwd = $_POST['repeatPwd'];
	$oldPwd = $_POST['oldPwd'];

	// Instantiate RegisterContr class
	include '../app/core/dbh.classes.php';

	// use sendmail trait
	require '../vendor/autoload.php';
	include '../app/core/sendMail.classes.php';

	include '../app/models/updatePassword.classes.php';
	include '../app/controllers/updatePassword-contr.classes.php';

	$updatePassword = new UpdatePasswordContr($uid, $pwd, $repeatPwd, $oldPwd); // create new object

	// Running error handlers and user registration
	$updatePassword->updatePasswordUser();

	// Forward user back to account settings
	$_SESSION['success'] = 'Success! You updated your password, we sent your email a confirmation';
	header('Location: ../dashboard.php');

} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../dashboard.php");
    die();
}