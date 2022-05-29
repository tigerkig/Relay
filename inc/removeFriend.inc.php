<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $friendUid = $_POST['uid'];
    $uid = $_SESSION['UID'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/removeFriend.classes.php';
    include '../app/controllers/removeFriend-contr.classes.php';
    
    // Create new message object
    $removeFriend = new RemoveFriendContr($friendUid, $uid);

    // Running error handlers and user registration
    $removeFriend->removeFriendUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}