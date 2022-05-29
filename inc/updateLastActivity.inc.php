<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $uid = $_SESSION['UID'];

    // Instantiate Login controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/updateLastActivity.classes.php';
    include '../app/controllers/updateLastActivity-contr.classes.php';
    
    // Create new message object
    $updateLastActivity = new UpdateLastActivityContr($uid);

    // Running error handlers and user registration
    $updateLastActivity->updateLastActivityUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}