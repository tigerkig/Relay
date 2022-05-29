<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $msg = $_POST['msg'];
    $senderId = $_SESSION['UID'];
    $receiverId = $_POST['receiver_id'];
    // $groupid = $_POST['groupid'];
    empty($_POST['groupid']) ? $groupid = null : $groupid = $_POST['groupid'];
    $type = $_POST['type'];
    // $_SESSION['booms'] = array($msg,$senderId,$receiverId,$type,$groupid);

    // Instantiate Login controller class
    include '../app/core/dbh.classes.php';

    // Load Composer's autoloader
    @include_once '../vendor_pgp/autoload.php';

    // Encryption scripts
    require_once '../lib/openpgp.php';
    require_once '../lib/openpgp_crypt_rsa.php';
    require_once '../lib/openpgp_crypt_symmetric.php';

    // include '../app/core/updateLastActivity.classes.php';
    include '../app/models/sendMessage.classes.php';
    include '../app/controllers/sendMessage-contr.classes.php';
    
    // Create new message object
    $sendMessage = new SendMessageContr($msg, $senderId, $receiverId, $type, $groupid);

    // Running error handlers and user registration
    $sendMessage->sendMessageUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}