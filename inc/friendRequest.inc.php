<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $response = $_POST['response'];
    $senderUid = $_POST['sender_id'];
    $uid = $_SESSION['UID'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/friendRequest.classes.php';
    include '../app/controllers/friendRequest-contr.classes.php';
    
    // Create new friend request response object
    $friendRequest = new FriendRequestContr($response, $senderUid, $uid);

    // Running error handlers and responding to friend requests
    $friendRequest->friendRequestUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}