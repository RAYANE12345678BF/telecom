<?php
require_once __DIR__ . '/../../vendor/autoload.php';

if (!session_id()) {
    session_start();
}

$userInformation = fetch_user_information($_SESSION['user_id']);

if (empty($userInformation['matricule'])) {
    session([
        'status' => 'entre le matricule pour utiliser les services',
        'status_icon' => 'warning',
    ]);

    if (!is_url_same(dashboard_url('/'))) {
        redirect(dashboard_url('/'));
    }
}

if (empty($userInformation['superior_id']) && $userInformation['role']['nom'] != 'Directeur') {
    session([
        'status' => 'selecter votre superieur pour utiliser les services',
        'status_icon' => 'warning',
    ]);

    if (!is_url_same(dashboard_url('/'))) {
        redirect(dashboard_url('/'));
    }
}