<?php

require_once __DIR__ . '/../vendor/autoload.php';


if( !session_id() ){
    session_start();
}

$action = $_POST['action'] ?? null;

if( !$action ){
    throw new Exception("bad call to this file");
}


switch($action){
    case 'add_work_day':
        $user_id = $_SESSION['user_id'];
        $date = $_POST['date'];
        $benefited = false;

        $response = add_work_day($user_id, $date, $benefited);

        send_json_response($response);
        break;

    case 'remove_work_day':
        $user_id = $_SESSION['user_id'];
        $date = $_POST['date'];
        $benefited = false;

        $response = remove_work_day($user_id, $date);

        send_json_response($response);
        break;
}