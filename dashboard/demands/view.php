<?php

require_once __DIR__ . '/../../vendor/autoload.php';

if (!session_id()) {
    session_start();
}

$id = $_GET['demand'];
$type = $_GET['type'];

switch ($type) {
    case 'conge_annual':
        $uri = 'demands/conge/annual.php';
        break;
    case 'conge_rc':
        $uri = 'demands/conge/rc.php';
        break;
    case 'conge_malady':
        $uri = 'demands/conge/malady.php';
        break;
    case 'conge_maternity':
        $uri = 'demands/conge/maternity.php';
        break;
    case 'conge_annual':
        $uri = 'demands/conge/annual.php';
        break;
    case 'deplacement':
        $uri = 'demands/deplacement';
        break;
    case 'formation':
        $uri = 'demands/formation';
        break;
    case 'leave':
        $uri = 'demands/leave';
        break;
    case 'mission':
        $uri = 'demands/mission';
        break;
    default:
    throw new Exception("bad calling this page");
}

redirect(url(sprintf("dashboard/%s?demand_id=%s", $uri, $id)));
