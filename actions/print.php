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
$demand_user = fetch_user_information($demand['employee_id'], false);


$pdf = new TCPDF();
$pdf->SetCreator('YourApp');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Demand Request');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();

$fullname = $demand_user['nom']. " ". $demand_user['prenom'];
// Build HTML content
$html = <<<HTML
<h2 style="text-align: center;">Demand Request Summary</h2>
<p><strong>Date:</strong> {date}</p>

<table cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td><strong>Employee Name:</strong> { $fullname }</td>
        <td><strong>Matricule:</strong> {$demand_user['matricule']}</td>
    </tr>
    <tr>
        <td><strong>Email:</strong> {$demand_user['email_professionnel']}</td>
        <td><strong>Department:</strong> {$demand_user['department']['nom']}</td>
    </tr>
</table>

<br>

<table cellspacing="0" cellpadding="5" border="1">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Demand Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Duration (days)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{$demand['type']}</td>
            <td>{$demand['date_debut']}</td>
            <td>{$demand['date_fin']}</td>
            <td>{$demand['duree']}</td>
            <td>{$demand['status']}</td>
        </tr>
    </tbody>
</table>

<br>
<p style="text-align: right;">Generated on: {gen_date}</p>
HTML;

$html = str_replace(
    ['{date}', '{gen_date}'],
    [date('Y-m-d'), date('Y-m-d H:i')],
    $html
);

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('demand_request.pdf', 'I'); // Output to browser