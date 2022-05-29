<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	
	// Grab post data
	$uid = $_SESSION['UID'];
	$pwd = $_POST['pwd'];
	$notify = $_POST['notify'];
	$security = $_POST['security'];

	// Instantiate RegisterContr class
	include '../app/core/dbh.classes.php';

	// use sendmail trait
	require '../vendor/autoload.php';
	include '../app/core/sendMail.classes.php';

	include '../app/models/updatePreferences.classes.php';
	include '../app/controllers/updatePreferences-contr.classes.php';

	$updatePreferences = new UpdatePreferencesContr($uid, $pwd, $notify, $security); // create new object

	// Running error handlers and user registration
	$updatePreferences->updatePreferencesUser();

	// Forward user back to account settings
	$_SESSION['success'] = 'Success! You have updated your preferences and was sent a confirmation email';
	header('Location: ../dashboard.php');

} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../dashboard.php");
    die();
}