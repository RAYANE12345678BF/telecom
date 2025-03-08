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

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, $info, $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
        break;

    case 'conge_malady':
        $description = $_POST['description'] ?? null;
        $info['content'] = uploadPdf('info', '/employees/conge');

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
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
        redirect(url('dashboard'));
        break;

    case 'conge_rc':
        $description = $_POST['description'] ?? null;
        $info = $_POST['info'] ?? null;

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, null, $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
        break;

    case 'formation':
        $formation_data = [
            'grade' => $_POST['grade'] ?? null,
            'service' => $_POST['service'] ?? null,
            'intitule' => $_POST['intitule'] ?? null,
            'place' => $_POST['place'] ?? null,
        ];

        $duree = $_POST['duree'] ?? 4;
        $info['content'] = $formation_data;

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        $_SESSION['status'] = 'sucessfully demand send';
        redirect(url('dashboard'));
        break;

    default:
        $_SESSION['error'] = "not a valid action";
        die("dsds");
        redirect_back();
}
