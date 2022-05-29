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
		$code = $_POST['code'];
		$email = $_SESSION['EMAIL'];

		// Instantiate Login controller class
		include '../app/core/dbh.classes.php';
		include '../app/models/verifyForgot.classes.php';
		include '../app/controllers/verifyForgot-contr.classes.php';
		
		$verifyForgot = new VerifyForgotContr($code, $email); // create new object

		// Running error handlers and user registration
		$verifyForgot->verifyForgotUser();

		header('Location: ../dashboard.php');
	}
	else
	{
        // Not verified - show form error
        $_SESSION['error'] = 'Error! Captcha has failed, please try again '.$recaptcha->score;
        header("Location: ../index.php?verify");
	}
} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../index.php?verify");
    die();
}