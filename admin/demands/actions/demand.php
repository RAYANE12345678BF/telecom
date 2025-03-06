<?php

include __DIR__ . '/../../../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

$action = $_POST['action'] ?? null;

if( $action == 'change_status' ){
    change_status($_POST['demand_id'], $_POST['status']);
    send_json_response([
        'status' => 'ok',
        'message' => 'updated successfully'
    ]);
    exit();
}

$demand_type = $_POST['demand_type'] ?? 'conge_annual';

if( !$demand_type ){
    throw new Exception("Demand type is required");
}

switch( $demand_type ){
    case 'conge_annual':
        $duree = $_POST['duree'] ?? null;
        $description = $_POST['description'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $info = $_POST['info'] ?? null;

        $demand_id = demand( $_SESSION['user_id'], $duree, $description, $start_date, $end_date, $info );
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
        break;

    case 'conge_malady':
        $duree = $_POST['duree'] ?? null;
        $description = $_POST['description'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $info = $_POST['info'] ?? null;

        $demand_id = demand( $_SESSION['user_id'], $duree, $description, $start_date, $end_date, $info );
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
        break;

    case 'conge_maternity':
        $duree = $_POST['duree'] ?? null;
        $description = $_POST['description'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $info = $_POST['info'] ?? null;

        $demand_id = demand( $_SESSION['user_id'], $duree, $description, $start_date, $end_date, $info );
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
        break;

    case 'conge_rc':
        $duree = $_POST['duree'] ?? null;
        $description = $_POST['description'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $info = $_POST['info'] ?? null;

        $demand_id = demand( $_SESSION['user_id'], $duree, $description, $start_date, $end_date, $info );
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
        break;

    default:
        $_SESSION['error'] = "not a valid action";
        die("dsds");
        redirect_back();
    }