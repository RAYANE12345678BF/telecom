<?php

include __DIR__ . '/../../../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

$demand_type = $_POST['demand_type'] ?? 'annual';

if( !$demand_type ){
    throw new Exception("Demand type is required");
}

switch( $demand_type ){
    case 'annual':
        $duree = $_POST['duree'] ?? null;
        $description = $_POST['description'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $info = $_POST['info'] ?? null;
        $status = 'waiting'

        $demand_id = demand( $_SESSION['user_id'], $duree, $description, $start_date, $end_date, $info );
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
}