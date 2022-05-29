<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $uid = $_SESSION['UID'];
    $receiver = $_POST['uid'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/cancelFriendRequest.classes.php';
    include '../app/controllers/cancelFriendRequest-contr.classes.php';
    
    // Create new message object
    $cancelFriendRequest = new CancelFriendRequestContr($uid, $receiver);

    // Running error handlers and user registration
    $cancelFriendRequest->cancelFriendRequestUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}