<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $uid = $_SESSION['UID'];
    $chatUid = $_POST['chat_uid'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/changeReadStatus.classes.php';
    include '../app/controllers/changeReadStatus-contr.classes.php';
    
    // Create new changeReadStatus object
    $changeReadStatus = new ChangeReadStatusContr($uid, $chatUid);

    // Running error handlers and changeReadStatus
    $changeReadStatus->changeReadStatusUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}