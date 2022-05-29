<?php
session_start();

if ($_SESSION['loggedin'] === 1 || $_SESSION['loggedin'] === 2) 
{
    // Grab post data
    $uid = $_SESSION['UID'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/logout.classes.php';
    include '../app/controllers/logout-contr.classes.php';
    
    // Create new message object
    $logout = new LogoutContr($uid);

    // Running error handlers and user registration
    $logout->logoutUser();

    header('Location: ../index.php?register');

} 
else
{
    $_SESSION['error'] = 'Error! You are already logged out';
    header("Location: ../index.php");
    die();
}