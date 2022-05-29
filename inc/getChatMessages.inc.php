<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $senderId = $_SESSION['UID'];
    $receiverId = $_POST['receiver_id'];
    $username = $_SESSION['USERNAME'];
    $type = $_POST['type'];

    $_SESSION['getchatmsg'] = array($senderId,$receiverId,$username,$type);

    // Instantiate getChatMessages controller class
    include '../app/core/dbh.classes.php';

    // Load Composer's autoloader
    @include_once '../vendor_pgp/autoload.php';

    // Encryption scripts
    require_once '../lib/openpgp.php';
    require_once '../lib/openpgp_crypt_rsa.php';
    require_once '../lib/openpgp_crypt_symmetric.php';

    include '../app/models/getChatMessages.classes.php';
    include '../app/controllers/getChatMessages-contr.classes.php';
    
    // Create new message object
    $getChatMessages = new GetChatMessagesContr($senderId, $receiverId, $username, $type);

    // Running error handlers and user registration
    $getChatMessages->getChatMessagesUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}