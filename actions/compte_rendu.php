<?php

require_once __DIR__ . '/../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

$demand_id = $_POST['demand_id'] ?? null;

if( !$demand_id ){
    throw new Exception('bad calling this file');
}

$demand = fetch_demand($demand_id);

$nature = $_POST['nature'];
$description = $_POST['description'];

$info = [
    'nature' => $nature,
    'description' => $description
];

if( $demand['type'] == 'deplacement' ){
    $info['justify'] = $_POST['justify'];
    $info['raisons'] = $_POST['raisons'];
}

$info = json_encode($info);

if( !$demand['compte_rendu'] ){
    create_compte_rendu($demand_id, $info);
}else{
    update_compte_rendu($demand_id, $info);
}
$_SESSION['status'] = "la demande a été traitée avec succès";
redirect_back();