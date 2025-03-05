<?php

require_once __DIR__ . '/../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

if( empty($_SESSION['user_id']) || ! isset($_SESSION['user']) ){
    redirect('auth/login.php');
}

$action = $_POST['action'] ?? 'get_all';

if( $action == 'set_read' ){
    $id  = $_POST['id'];

    read_notification($id);
    send_json_response([
        'message' => 'done'
    ]);
}

$notifications = get_notifications($_SESSION['user_id']);

send_json_response($notifications);

?>