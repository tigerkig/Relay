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
		$remember = $_POST['remember'];

		// Instantiate Login controller class
		include '../app/core/dbh.classes.php';
		// Load Composer's autoloader
		require '../vendor/autoload.php';
		include '../app/core/sendMail.classes.php';
		include '../app/models/login.classes.php';
		include '../app/controllers/login-contr.classes.php';
		
		$login = new LoginContr($uid, $pwd, $remember); // create new object

		// Running error handlers and user registration
		$login->loginUser();

		header('Location: ../dashboard.php');
	}
	else
	{
        // Not verified - show form error
        $_SESSION['error'] = 'Error! Captcha has failed, please try again '.$recaptcha->score;
        header("Location: ../index.php");
	}
} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../index.php");
    die();
}