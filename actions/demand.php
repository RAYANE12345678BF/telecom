<?php

include __DIR__ . '/../vendor/autoload.php';

if (!session_id()) {
    session_start();
}

$action = $_POST['action'] ?? null;

if ($action == 'change_status') {
    change_status($_POST['demand_id'], $_POST['status']);
    send_json_response([
        'status' => 'ok',
        'message' => 'updated successfully'
    ]);
    exit();
}

$demand_type = $_POST['demand_type'] ?? 'conge_annual';

if (!$demand_type) {
    throw new Exception("Demand type is required");
}


$duree = $_POST['duree'] ?? null;
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$description = $_POST['description'] ?? null;
$info = [
    'type' => in_array($demand_type, ['conge_maternity', 'conge_malady']) ? 'file' : 'text',
    'content' => null,
];


switch ($demand_type) {
    case 'conge_annual':
        $duree = $_POST['duree'] ?? null;
        $description = $_POST['description'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $info['content'] = $_POST['info'] ?? null;

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('profiles'));
        break;

    case 'conge_malady':
        $description = $_POST['description'] ?? null;
        $info['type'] = 'file';
        $info['content'] = uploadPdf('info', '/employees/conge');

        if( $info['content'] == false ){
            $_SESSION['error'] = 'unable to save the file';
            redirect_back();
        }

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('profiles'));
        break;

    case 'conge_maternity':

        $description = $_POST['description'] ?? null;
        $info['content'] = uploadPdf('info', '/employees/conge');
        if (!$info['content']) {
            $_SESSION['error'] = "verify if the file is a pdf file";
            redirect_back();
        }

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('profiles'));
        break;

    case 'conge_rc':
        $description = $_POST['description'] ?? null;
        $info['content'] = "no details";

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('profiles'));
        break;

    case 'formation':
        $formation_data = [
            'intitule' => $_POST['intitule'] ?? null,
            'place' => $_POST['place'] ?? null,
        ];

        $duree = $_POST['duree'] ?? 4;
        $info['content'] = $formation_data;
        $info['type'] = 'keys';

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('profiles'));
        break;
    case 'mission':
        $mission_data = [
            'destination' => $_POST['destination'] ?? null,
            'leave date' => $_POST['leave_date'] ?? null,
            'leave hour' => $_POST['leave_hour'] ?? null,
            'come date' => $_POST['come_date'] ?? null,
            'come hour' => $_POST['come_hour'] ?? null,
            'motif' => $_POST['motif'] ?? null,
        ];

        $duree = $_POST['duree'] ?? 4;
        $info['content'] = $mission_data;
        $info['type'] = 'keys';

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('profiles'));
        break;
    case 'deplacement':
        $mission_data = [
            'leave date' => $_POST['leave_date'] ?? null,
            'leave hour' => $_POST['leave_hour'] ?? null,
            'come date' => $_POST['come_date'] ?? null,
            'come hour' => $_POST['come_hour'] ?? null,
            'motif' => $_POST['motif'] ?? null,
        ];

        $duree = $_POST['duree'] ?? 4;
        $info['content'] = $mission_data;
        $info['type'] = 'keys';

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('profiles'));
        break;
    case 'leave':
        $mission_data = [
            'leave date' => $_POST['leave_date'] ?? null,
            'leave hour' => $_POST['leave_hour'] ?? null,
            'come date' => $_POST['come_date'] ?? null,
            'come hour' => $_POST['come_hour'] ?? null,
            'motif' => $_POST['motif'] ?? null,
        ];

        $duree = $_POST['duree'] ?? 4;
        $info['content'] = $mission_data;
        $info['type'] = 'keys';

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('profiles'));
        break;

    case 'support':
        $message = $_POST['message'] ?? null;
        $type = $_POST['type'] ?? null;

        $demand_id = insert_support($_SESSION['user_id'], $message, $type); 
        $_SESSION['status'] = 'sucessfully support send';
        redirect(url('profiles'));
        break;

    default:
        $_SESSION['error'] = "not a valid action";
        die("dsds");
        redirect_back();
}
