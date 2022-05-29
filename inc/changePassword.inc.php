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
		$pwd = $_POST['pwd'];
        $pwdRepeat = $_POST['pwdRepeat'];
        $code = $_SESSION['code'];
		$email = $_SESSION['EMAIL'];

		// Instantiate Login controller class
		include '../app/core/dbh.classes.php';
		// Load Composer's autoloader
		require '../vendor/autoload.php';
		include '../app/core/sendMail.classes.php';
		include '../app/models/changePassword.classes.php';
		include '../app/controllers/changePassword-contr.classes.php';
		
		$changePassword = new ChangePasswordContr($pwd, $pwdRepeat, $code, $email); // create new object

		// Running error handlers and user registration
		$changePassword->changePasswordUser();

		header('Location: ../dashboard.php');
	}
	else
	{
        // Not verified - show form error
        $_SESSION['error'] = 'Error! Captcha has failed, please try again '.$recaptcha->score;
        header("Location: ../index.php?changePassword");
	}
} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../index.php?changePassword");
    die();
}