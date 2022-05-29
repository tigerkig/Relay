<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	
	// Grab post data
	$uid = $_SESSION['UID'];
	$groupid = $_POST['groupid'];

	// Instantiate RegisterContr class
	include '../app/core/dbh.classes.php';

	include '../app/models/joinPublicGroupchat.classes.php';
	include '../app/controllers/joinPublicGroupchat-contr.classes.php';

	$joinPublicGroupchat = new JoinPublicGroupchatContr($uid, $groupid); // create new object

	// Running error handlers and user registration
	$joinPublicGroupchat->joinPublicGroupchatUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}