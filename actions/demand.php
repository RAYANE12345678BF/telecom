<?php

include __DIR__ . '/../vendor/autoload.php';

if (!session_id()) {
    session_start();
}

$action = $_POST['action'] ?? null;

if ($action == 'change_status') {
    set_decision($_POST['demand_id'], $_SESSION['user_id'], $_POST['status']);
    send_json_response([
        'status' => 'ok',
        'message' => 'la modification a été effectuée avec succès'
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
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
        
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        break;

    case 'conge_malady':
        $description = $_POST['description'] ?? null;
        $info['type'] = 'file';
        $info['content'] = uploadPdf('info', '/employees/conge');

        if( $info['content'] == false ){
            $_SESSION['error'] = 'le fichier n\'a pas pu être enregistré';
            redirect_back();
        }

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
        break;

    case 'conge_maternity':
        $description = $_POST['description'] ?? null;
        $info['content'] = uploadPdf('info', '/employees/conge');
        if (!$info['content']) {
            $_SESSION['error'] = "le fichier n'a pas pu être enregistré";
            redirect_back();
        }

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
        break;
        
    case 'conge_rc':
        $description = $_POST['description'] ?? null;
        $info['content'] = "no details";

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
        redirect(url('dashboard'));
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
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
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
        $start_date = $mission_data['leave date'];
        $end_date = $mission_data['come date'];

        $duree = $_POST['duree'] ?? 4;
        $info['content'] = $mission_data;
        $info['type'] = 'keys';

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
        break;
    case 'deplacement':
        $mission_data = [
            'leave date' => $_POST['leave_date'] ?? null,
            'leave hour' => $_POST['leave_hour'] ?? null,
            'come date' => $_POST['come_date'] ?? null,
            'come hour' => $_POST['come_hour'] ?? null,
            'motif' => $_POST['motif'] ?? null,
        ];

        $start_date = $mission_data['leave date'];
        $end_date = $mission_data['come date'];

        $duree = $_POST['duree'] ?? 4;
        $info['content'] = $mission_data;
        $info['type'] = 'keys';

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
        break;
    case 'leave':
        $mission_data = [
            'leave date' => $_POST['leave_date'] ?? null,
            'leave hour' => $_POST['leave_hour'] ?? null,
            'come date' => $_POST['come_date'] ?? null,
            'come hour' => $_POST['come_hour'] ?? null,
            'motif' => $_POST['motif'] ?? null,
        ];

        $start_date = $mission_data['leave date'];
        $end_date = $mission_data['come date'];

        $duree = $_POST['duree'] ?? 4;
        $info['content'] = $mission_data;
        $info['type'] = 'keys';

        $demand_id = demand($_SESSION['user_id'], $duree, $description, $start_date, $end_date, json_encode($info), $demand_type);
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
        break;

    case 'support':
        $message = $_POST['message'] ?? null;
        $type = $_POST['type'] ?? null;

        $demand_id = insert_support($_SESSION['user_id'], $message, $type); 
        add_lifecycle_entry($demand_id, $_SESSION['user']['superior_id']);
        $_SESSION['status'] = 'la demande a été envoyée avec succès';
        break;

    default:
        $_SESSION['error'] = "not a valid action";
        die("dsds");
        redirect_back();
}

push_demand_creation_notification($demand_id);
redirect(url('dashboard'));