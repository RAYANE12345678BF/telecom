<?php

include __DIR__ . '/../vendor/autoload.php';

if( !session_id() ){
    session_start();
}

if( empty($_SESSION['user_id']) || ! isset($_SESSION['user']) ){
    redirect('auth/login.php');
}

$role = get_role($_SESSION['user']['role_id']);

switch( $role['nom'] ){
    case 'Employé':
        redirect(url('employee/profile.php'));
        break;
    case 'Chef de Service':
        redirect(url('admin/profile.php'));
        break;
    case 'Chef de Département':
        redirect(url('admin/profile.php'));
        break;
    case 'Sous-Directeur':
        redirect(url('admin/profile.php'));
        break;
    case 'Directeur':
        redirect(url('admin/profile.php'));
        break;
    default:
        throw new \Exception('Unexpected value');
}


