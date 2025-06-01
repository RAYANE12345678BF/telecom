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
    $birth_day = empty($_POST['birth_day']) ? null : $_POST['birth_day'];
    $birth_place = empty($_POST['birth_place']) ? null : $_POST['birth_place'];
    $etat_civil = empty($_POST['etat_cevil']) ? null : $_POST['etat_cevil'];
    $nom = empty($_POST['nom']) ? null : $_POST['nom'];
    $prenom = empty($_POST['prenom']) ? null : $_POST['prenom'];

    if( !$phone || !$birth_day || !$birth_place || !$etat_civil || !$nom || !$prenom ){
        send_json_response([
            'success' => false,
            'message' => 'tous les champs sont obligatoires'
        ]);
    }

    $errors = validate($_POST, [
        'phone' => ['required', 'phone'],
        'birth_day' => ['required'],
        'birth_place' => ['required', 'alpha'],
        'etat_cevil' => ['required'],
        'nom' => ['required', 'alpha'],
        'prenom' => ['required', 'alpha']
    ]);

    if( is_array($errors) ){
        $fields = array_keys($errors);
        send_json_response([
            'success' => false,
            'message' => $errors[$fields[0]]
        ]);
    }



    $user_id = $_SESSION['user_id'];

    $data = compact('phone', 'birth_day', 'birth_place', 'etat_civil', 'nom', 'prenom');

    $user = update_user($user_id, $data);

    if( $user ){
        $_SESSION['user'] = fetch_user_information($_SESSION['user_id'], false);
        send_json_response([
            'message' => "les informations ont été mises à jour avec succès",
            'success' => true
        ]);
    }else{
        throw new Exception('error was occured');
    }

    break;
    case 'save_prof':
        $matricule = empty($_POST['matricule'])? null : $_POST['matricule'];
        $departement_id = empty($_POST['department_id']) ? null : $_POST['department_id'];
        $service_id =  empty($_POST['service_id']) ? null : $_POST['service_id'];
        $superior_id = empty($_POST['superior_id']) ? null : $_POST['superior_id'];
        $start_date = empty($_POST['start_date']) ? null : $_POST['start_date'];
        $substitute_id = empty($_POST['substitute_id']) ? null : $_POST['substitute_id'];

        if( ($u = get_user_with('matricule', $matricule)) != null && $u['id'] != $_SESSION['user_id']  ){
            send_json_response([
                'success' => false,
                'message' => 'matricule deja existant'
            ]);
        }

        $errors = validate($_POST, [
            'matricule' => ['required'],
            'department_id' => ['required'],
            'service_id' => ['required'],
            'superior_id' => ['required'],
            'start_date' => ['required'],
        ]);

        $user_id = $_SESSION['user_id'];
    
        $data = compact('matricule', 'departement_id', 'start_date', 'service_id', 'superior_id', 'substitute_id');
    
        $user = update_user($user_id, $data);
    
        if( $user ){
            $_SESSION['user'] = fetch_user_information($_SESSION['user_id'], false);
            send_json_response([
                'success' => true,
                'message' => "les informations ont été mises à jour avec succès"
            ]);
        }else{
            throw new Exception('error was occured');
        }
    
        break;

    case 'save_password':
        $actual_password = $_POST['actual_passwword'] ?? null;
        $new_password = $_POST['password'] ?? null;
        $password_confirmation = $_POST['password_confirmation'] ?? null;
        $user_id = $_SESSION['user_id'];

        if( !$actual_password || !$new_password || !$password_confirmation ){
            send_json_response([
                'success' => false,
                'message' => 'tous les champs sont obligatoires'
            ]);
        }


        change_password($user_id, $actual_password, $new_password, $password_confirmation);

        break;
}