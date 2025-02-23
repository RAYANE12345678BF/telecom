<?php

include __DIR__ . '/../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

print_r($_SESSION);

if( empty($_SESSION['user_id']) || ! isset($_SESSION['user']) ){
    redirect('auth/login.php');
}

$role = get_role($_SESSION['user']['role_id']);

switch( $role['nom'] ){
    case 'Employé':
        redirect(url('employee/profile.php'));
        break;
    case 'Chef de Service':
        break;
    case 'Chef de Département':
        break;
    case 'Sous-Directeur':
        break;
    case 'Directeur':
        break;
    default:
        throw new \Exception('Unexpected value');
}


