<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab session data
    $uid = $_SESSION['UID'];
    $firstLogin = $_SESSION['first_login'];
    $loggedIn = $_SESSION['loggedin'];

    // Instantiate Welcome Screen class
    include '../app/core/dbh.classes.php';

    include '../app/models/welcomeScreen.classes.php';
    include '../app/controllers/welcomeScreen-contr.classes.php';

    $welcomeScreen = new WelcomeScreenContr($uid, $firstLogin, $loggedIn); // create new object

    // Running error handlers and user registration
    $welcomeScreen->welcomeScreenUser();

    // Forward user to the dashboard
    header('Location: ../dashboard.php');
} 
else
{
    $_SESSION['error'] = 'Error! You do not have permission to access that request';
    header("Location: ../index.php");
    die();
}