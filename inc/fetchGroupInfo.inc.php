<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $groupid = $_POST['groupid'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/fetchGroupInfo.classes.php';
    include '../app/controllers/fetchGroupInfo-contr.classes.php';

    // Create new message object
    $fetchGroupInfo = new FetchGroupInfoContr($groupid);

    // Running error handlers and user registration
    $fetchGroupInfo->fetchGroupInfoUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}