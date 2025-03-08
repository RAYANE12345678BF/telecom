<?php


require_once __DIR__ . '/../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

$action = $_POST['action'] ?? "";

if(  empty($action) ){
    throw new Exception('the action must be provided');
}


switch($action){
 case 'save':
    $phone = $_POST['phone'] ?? null;
    $birth_day = $_POST['birth_day'] ?? null;
    $birth_place = $_POST['birth_place'] ?? null;
    $etat_civil = $_POST['etat_cevil'] ?? null;
    $nom = $_POST['nom'] ?? null;
    $prenom = $_POST['prenom'] ?? null;

    $user_id = $_SESSION['user_id'];

    $data = compact('phone', 'birth_day', 'birth_place', 'etat_civil', 'nom', 'prenom');

    $user = update_user($user_id, $data);

    if( $user ){
        send_json_response([
            'message' => "informations updated successfully"
        ]);
    }else{
        throw new Exception('error was occured');
    }

    break;
}