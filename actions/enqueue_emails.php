<?php

require_once __DIR__ . '/../vendor/autoload.php';

$pdo = load_db();
session_start();

// POST: employee_ids[], subject, content
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST);
    $employeeIds = $_POST['employee_ids'] ?? [];
    $subject = trim($_POST['subject'] ?? '');
    $content = trim($_POST['content'] ?? '');

    $attachmentPath = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $uploadDir = __DIR__ . '/../storage/email_attachments/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['attachment']['name']);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $filePath)) {
            $attachmentPath = $filePath;
        }
    }

    if (!$employeeIds || !$subject || !$content) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing fields']);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO email_jobs (employee_id, subject, content, attachment_path) VALUES (?, ?, ?, ?)");
    foreach (explode(',', $employeeIds) as $id) {
        $stmt->execute([$id, $subject, $content, $attachmentPath]);
    }

    echo json_encode(['success' => true, 'message' => 'Emails queued successfully.']);
}
