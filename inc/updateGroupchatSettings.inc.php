<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $uid = $_SESSION['UID'];
    $updateGroupchatObj = $_POST['updateGroupchatObj'];

    // Include profanity words list
    $profanity = file_get_contents('../inc/list.txt');
    $profanity = preg_split("/\\r\\n|\\r|\\n/", $profanity);

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/updateGroupchatSettings.classes.php';
    include '../app/controllers/updateGroupchatSettings-contr.classes.php';
    
    // Create new message object
    $updateGroupchatSettings = new UpdateGroupchatSettingsContr($uid, $updateGroupchatObj, $profanity);

    // Running error handlers and user registration
    $updateGroupchatSettings->updateGroupchatSettingsUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}