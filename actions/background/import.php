<?php


require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    $filePath = 'path_to_your_spreadsheet.xlsx'; // Replace with the actual file path
    $spreadsheet = IOFactory::load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();

    $rows = $worksheet->toArray(null, true, true, true);

    // Database connection (update with your DB credentials)
    $dsn = 'mysql:host=localhost;dbname=your_database;charset=utf8mb4';
    $username = 'your_username';
    $password = 'your_password';
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL for inserting data
    $stmt = $pdo->prepare("INSERT INTO employees (nom, matricule) VALUES (:nom, :matricule)");

    foreach ($rows as $index => $row) {
        // Skip the first row if it contains headers
        if ($index === 1) {
            continue;
        }

        $nom = isset($row['A']) ? trim($row['A']) : null;
        $matricule = isset($row['B']) ? trim($row['B']) : null;

        if ($nom && $matricule) {
            $stmt->execute([
                ':nom' => $nom,
                ':matricule' => $matricule,
            ]);
        }
    }

    echo "Users have been successfully imported into the employees table.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}