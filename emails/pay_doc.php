<?php
require_once __DIR__ . '/../vendor/autoload.php';
$id = 1;
// Get employee information
$employee = fetch_user_information($id);
$month = date('F Y'); // Current month and year
$payment_date = date('d/m/Y'); // Current date

// Calculate payment details
$base_salary = $employee['salary'] ?? 0;
$bonus = $employee['bonus'] ?? 0;
$deductions = $employee['deductions'] ?? 0;
$net_salary = $base_salary + $bonus - $deductions;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin de Paie - <?= $month ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #003366;
            padding-bottom: 20px;
        }
        .company-logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .document-title {
            color: #003366;
            font-size: 24px;
            margin: 10px 0;
        }
        .employee-info {
            margin-bottom: 30px;
        }
        .payment-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .payment-details th, .payment-details td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .payment-details th {
            background-color: #f5f5f5;
            text-align: left;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?= url('assets/images/logo.png') ?>" alt="Company Logo" class="company-logo">
        <h1 class="document-title">Bulletin de Paie</h1>
        <p>Période: <?= $month ?></p>
    </div>

    <div class="employee-info">
        <h2>Informations de l'Employé</h2>
        <p><strong>Nom:</strong> <?= $employee['nom'] . ' ' . $employee['prenom'] ?></p>
        <p><strong>Matricule:</strong> <?= $employee['matricule'] ?></p>
        <p><strong>Département:</strong> <?= $employee['department']['nom'] ?></p>
        <p><strong>Poste:</strong> <?= $employee['role']['nom'] ?></p>
    </div>

    <table class="payment-details">
        <thead>
            <tr>
                <th>Description</th>
                <th>Montant (DA)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Salaire de Base</td>
                <td><?= number_format($base_salary, 2) ?></td>
            </tr>
            <tr>
                <td>Primes et Bonus</td>
                <td><?= number_format($bonus, 2) ?></td>
            </tr>
            <tr>
                <td>Retenues</td>
                <td>-<?= number_format($deductions, 2) ?></td>
            </tr>
            <tr class="total-row">
                <td>Net à Payer</td>
                <td><?= number_format($net_salary, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                Signature de l'Employé
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Signature du Responsable RH
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Ce document est généré automatiquement le <?= $payment_date ?></p>
        <p>Pour toute question concernant votre bulletin de paie, veuillez contacter le service des ressources humaines.</p>
    </div>
</body>
</html>