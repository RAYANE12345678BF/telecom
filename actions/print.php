<?php

require_once __DIR__ . '/../vendor/autoload.php';

if( ! session_id() ){
    session_start();
}

use Dompdf\Dompdf;


define("None", null);

$demand_id = $_GET['demand'] ?? None;

if( $demand_id === None ){
    throw new Exception("invalid argument in the url");
}

$demand = fetch_demand($demand_id);

if( $demand['type'] != 'conge_annual' ){
    throw new Exception('this feature is for conge annual');
}

$demand_user = fetch_user_information($demand['employee_id'], false);


$pdf = new TCPDF();
$pdf->SetCreator('YourApp');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Demand Request');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

$data = [
    'matricule' => $demand_user['matricule'] ,
    'nom' => $demand_user['nom'],
    'prenom' => $demand_user['prenom'],
    'role' => $demand_user['role']['nom'],
    'description' => $demand['description'],
    'duree' => $demand['duree'],
    'date_debut' => $demand['date_debut'],
    'date_fin' => $demand['date_fin'],
    'extra_information' => $demand['info']['content'],
    'status' => $demand['status'],
];



$fullname = $demand_user['nom']. " ". $demand_user['prenom'];
// Build HTML content
$html = file_get_contents(__DIR__ . "/../prints/template.html");

foreach($data as $key => $value){
    $html = str_replace("{{ $key }}", $value, $html);
}

echo $html;
