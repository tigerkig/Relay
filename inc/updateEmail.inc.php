<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	
	// Grab post data
	$uid = $_SESSION['UID'];
	$email = $_POST['email'];
	$pwd = $_POST['password'];

	// Instantiate RegisterContr class
	include '../app/core/dbh.classes.php';

	// use sendmail trait
	require '../vendor/autoload.php';
	include '../app/core/sendMail.classes.php';

	include '../app/models/updateEmail.classes.php';
	include '../app/controllers/updateEmail-contr.classes.php';

	$updateEmail = new UpdateEmailContr($uid, $email, $pwd); // create new object

	// Running error handlers and user registration
	$updateEmail->updateEmailUser();

	// Forward user back to account settings
	$_SESSION['success'] = 'Success! You have updated your email and was sent a confirmation email';
	header('Location: ../dashboard.php');

} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../dashboard.php");
    die();
}