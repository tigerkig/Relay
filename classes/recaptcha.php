<?php
/*
This class is for Google recaptcha v3 info
*/

// Build POST request:
$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
$recaptcha_secret = '';
$recaptcha_response = $_POST['recaptcha_response'];

// Make and decode POST request:
$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
$recaptcha = json_decode($recaptcha);