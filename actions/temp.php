<?php

require_once __DIR__ . '/../vendor/autoload.php';
$pdo = require(__DIR__ . '/../db/connection.php');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

session_start();
$admin_id = $_SESSION['user_id'] ?? $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file']) || !$admin_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request or not authenticated']);
    exit;
}

try {
    // Create directory if not exist
    $storageDir = __DIR__ . '/../storage/pointage';
    if (!file_exists($storageDir)) {
        mkdir($storageDir, 0777, true);
    }

    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileTmpPath = $file['tmp_name'];

    $timestamp = date('Y-m-d_H-i-s');
    $newFileName = "pointage_{$timestamp}.xlsx";
    $newFilePath = $storageDir . '/' . $newFileName;

    $spreadsheet = IOFactory::load($fileTmpPath);
    $worksheet = $spreadsheet->getActiveSheet();

    $highestRow = $worksheet->getHighestRow();

    // Define columns
    $absentCol = 'P';         // Absent
    $justifiedCol = 'AD';      // New column
    $reasonCol = 'AE';         // New column

    // Set new headers
    $worksheet->setCellValue($justifiedCol . '2', 'Justified');
    $worksheet->setCellValue($reasonCol . '2', 'Reason');

    for ($row = 3; $row <= $highestRow; $row++) {
        $isAbsent = strtolower(trim($worksheet->getCell($absentCol . $row)->getValue()));

        if ($isAbsent === 'True') {
            $justified = rand(0, 1) ? 'Yes' : 'No';
            $reason = $justified === 'Yes' ? 'Medical Certificate' : '';
            $worksheet->setCellValue($justifiedCol . $row, $justified);
            $worksheet->setCellValue($reasonCol . $row, $reason);
        } else {
            $worksheet->setCellValue($justifiedCol . $row, '');
            $worksheet->setCellValue($reasonCol . $row, '');
        }
    }

    // Save updated file
    $writer = new Xlsx($spreadsheet);
    $writer->save($newFilePath);

    // Store in DB
    $uploadDate = date('Y-m-d');
    $stmt = $pdo->prepare("INSERT INTO appointment_files (admin_id, original_name, file_name, file_path)
                           VALUES (?, ?, ?, ?)");
    $stmt->execute([$admin_id, $fileName, $newFileName, $newFilePath]);

    echo json_encode([
        'success' => true,
        'message' => 'File uploaded and updated successfully.',
        'file_url' => '/storage/pointage/' . $newFileName
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Processing failed: ' . $e->getMessage()]);
}
