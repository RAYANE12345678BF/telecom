<?php

require __DIR__ . '/../vendor/autoload.php';


$type = $_POST['action'] ?? 'activate';
$id = $_POST['user_id'] ?? '';

if( empty($id) ){
    throw new Exception('the id must be provided');
}

handleAccount($type, $id);


send_json_response([
    'message' => 'les informations ont été mises à jour avec succès',
    'success' => true
]);