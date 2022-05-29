<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) 
{
	// Build POST request
    require '../classes/recaptcha.php';
	
	// Take action based on the score returned:
    if ($recaptcha->score >= 0.5) 
    {
    	// Verified

		// Grab post data
		$uid = $_POST['uid'];
		$pwd = $_POST['pwd'];
		$pwdRepeat = $_POST['pwdRepeat'];
		$email = $_POST['email'];
		$notify = $_POST['notify'];
        $agree = $_POST['agree'];

		// Include profanity words list
		$profanity = file_get_contents('../inc/list.txt');
		$profanity = preg_split("/\\r\\n|\\r|\\n/", $profanity);

		// Instantiate RegisterContr class
		include '../app/core/dbh.classes.php';

		@include_once '../vendor_pgp/autoload.php';
		require_once '../lib/openpgp.php';
		require_once '../lib/openpgp_crypt_rsa.php';
		require_once '../lib/openpgp_crypt_symmetric.php';

		include '../app/models/register.classes.php';
		include '../app/controllers/register-contr.classes.php';

		$register = new RegisterContr($uid, $pwd, $pwdRepeat, $email, $notify, $agree, $profanity); // create new object

		// Running error handlers and user registration
		$register->registerUser();

		// Forward user to front page
		$_SESSION['success'] = 'Success! You may now login to your account';
		header('Location: ../index.php');
	}
	else
	{
        // Not verified - show form error
        $_SESSION['error'] = 'Error! Captcha has failed, please try again '.$recaptcha->score;
        header("Location: ../index.php?register");
	}
} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../index.php?register");
    die();
}