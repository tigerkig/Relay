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

		// Instantiate Login controller class
		include '../app/core/dbh.classes.php';
		// Load Composer's autoloader
		require '../vendor/autoload.php';
		include '../app/core/sendMail.classes.php';
		include '../app/models/forgotRequest.classes.php';
		include '../app/controllers/forgotRequest-contr.classes.php';
		
		$forgotRequest = new ForgotRequestContr($uid); // create new object

		// Running error handlers and user registration
		$forgotRequest->forgotRequestUser();

		header('Location: ../dashboard.php');
	}
	else
	{
        // Not verified - show form error
        $_SESSION['error'] = 'Error! Captcha has failed, please try again '.$recaptcha->score;
        header("Location: ../index.php?forgot");
	}
} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../index.php?forgot");
    die();
}