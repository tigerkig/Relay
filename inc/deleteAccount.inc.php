<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	
	// Grab post data
	$uid = $_SESSION['UID'];
	$email = $_SESSION['EMAIL'];
	$pwd = $_POST['pwd'];
	$username = $_SESSION['USERNAME'];

	// Instantiate RegisterContr class
	include '../app/core/dbh.classes.php';

	include '../app/models/deleteAccount.classes.php';
	include '../app/controllers/deleteAccount-contr.classes.php';

	$deleteAccount = new DeleteAccountContr($uid, $email, $pwd, $username); // create new object

	// Running error handlers and user registration
	$deleteAccount->deleteAccountUser();

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