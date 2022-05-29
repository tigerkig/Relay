<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $receiver = $_POST['friendRequest'];
    $sender = $_SESSION['USERNAME'];
    $senderUid = $_SESSION['UID'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/addFriend.classes.php';
    include '../app/controllers/addFriend-contr.classes.php';
    
    // Create new message object
    $addFriend = new AddFriendContr($receiver, $sender, $senderUid);

    // Running error handlers and user registration
    $addFriend->addFriendUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}