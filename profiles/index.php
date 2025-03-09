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
    case 'GRH':
        redirect(url('profiles/drh/profile.php'));
        break;
    case 'Sous-Directeur':
        redirect(url('profiles/sous-directeur/profile.php'));
        break;
    case 'Directeur':
        redirect(url('profiles/directeur/profile.php'));
        break;
    default:
        redirect(url('profiles/employee/profile.php'));
}


