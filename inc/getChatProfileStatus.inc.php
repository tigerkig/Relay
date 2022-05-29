<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $chatUid = $_POST['chat_userid'];
    $type = $_POST['type'];
    $uid = $_SESSION['UID'];

    // Instantiate Get Chat Profile Status controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/getChatProfileStatus.classes.php';
    include '../app/controllers/getChatProfileStatus-contr.classes.php';
    
    // Create new getChatProfileStatus object
    $getChatProfileStatus = new GetChatProfileStatusContr($chatUid, $uid, $type);

    // Running error handlers and getChatProfileStatus
    $getChatProfileStatus->getChatProfileStatusUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}