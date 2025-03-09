<?php

include __DIR__ . '/../vendor/autoload.php';



if( ! session_id() ){
    session_start();
}

$action = filter_input(INPUT_POST, 'action');

if(empty($action)) {
    throw new Exception("Invalid action");
}


switch ($action) {
    case 'logout':
        unset($_SESSION['user_id']);
        unset($_SESSION['user']);
        session_destroy();
        redirect(url('auth/login.php'));
        break;

    case 'register':

        $nom = filter_input(INPUT_POST, 'nom');
        $prenom = filter_input(INPUT_POST, 'prenom');
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');
        $department = filter_input(INPUT_POST, 'department');
        $service = filter_input(INPUT_POST, 'service');
        $role = filter_input(INPUT_POST, 'role');


        $department_id = get_department_id($department);
        $service_id = get_service_id($service);
        $role_id = $role;


        $user = register($prenom, $nom, $email, $password, $department_id, $service_id, $role_id);

        if( isset($user['error']) ){
            $_SESSION['error'] = $user['error'];
            redirect_back();
        }else{
            $grh_id = get_grh_id();
            push_user_creation_notification($grh_id);
            $_SESSION['info'] = "a notification has sent to the grh, and he will review ur account soon";
            redirect_back();
        }

        break;
    case 'login':
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');


        if(empty($email) || empty($password)) {
            $_SESSION['error'] = 'you forgot password or email is empty';
            redirect_back();
        }

        if(!$email){
            $_SESSION['error'] = 'invalid email';
            redirect_back();
        }

        $user = login($email, $password);

        if( isset($user['error']) ){
            $_SESSION['error'] = $user['error'];
            redirect_back();
        }
        save_user($user);
        redirect(url('profiles'));
        exit();
        break;

        default:
            throw new Exception("Invalid action");
}