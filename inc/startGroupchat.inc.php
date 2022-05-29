<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	
	// Grab post data
	$uid = $_SESSION['UID'];
	$name = $_POST['name'];
	$desc = $_POST['desc'];
	$type = $_POST['type'];

	// Instantiate RegisterContr class
	include '../app/core/dbh.classes.php';

	include '../app/models/startGroupchat.classes.php';
	include '../app/controllers/startGroupchat-contr.classes.php';

	$startGroupchat = new StartGroupchatContr($uid, $name, $desc, $type); // create new object

	// Running error handlers and user registration
	$startGroupchat->startGroupchatUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}