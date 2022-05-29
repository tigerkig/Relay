<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $uid = $_SESSION['UID'];
    $obj = $_POST['obj'];
    $_SESSION['poopoo2'] = $obj;

    // Instantiate Add Friend controller class
    include '../app/core/dbh.classes.php';

    include '../app/models/groupchatMembersAdmin.classes.php';
    include '../app/controllers/groupchatMembersAdmin-contr.classes.php';
    
    // Create new message object
    $groupchatMembersAdmin = new GroupchatMembersAdminContr($uid, $obj);

    // Running error handlers and user registration
    $groupchatMembersAdmin->groupchatMembersAdminUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}