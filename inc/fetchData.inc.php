<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $uid = $_SESSION['UID'];

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/fetchData.classes.php';
    include '../app/controllers/fetchData-contr.classes.php';
    
    // Create new message object
    $fetchData = new FetchDataContr($uid);

    // Running error handlers and user registration
    $fetchData->fetchDataUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}