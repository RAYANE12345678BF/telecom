<?php

require_once __DIR__ . '/../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

if( empty($_SESSION['user_id']) || ! isset($_SESSION['user']) ){
    redirect('auth/login.php');
}

$notifications = get_notifications($_SESSION['user_id']);

send_json_response($notifications);

?>