<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $groupid = $_POST['groupid'];
    $uid = $_SESSION['UID'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/leaveGroupchat.classes.php';
    include '../app/controllers/leaveGroupchat-contr.classes.php';
    
    // Create new message object
    $leaveGroupchat = new LeaveGroupchatContr($groupid, $uid);

    // Running error handlers and user registration
    $leaveGroupchat->leaveGroupchatUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}