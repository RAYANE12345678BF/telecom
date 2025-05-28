<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$pdo = load_db();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mailer = new PHPMailer(true);

// Gmail SMTP settings
$gmail_user = 'karimaouaouda.officiel@gmail.com';
$gmail_app_password = ; // use app-specific password

try {
    $stmt = $pdo->prepare("SELECT ej.*, e.email_professionnel 
                           FROM email_jobs ej 
                           JOIN employees e ON ej.employee_id = e.id 
                           WHERE ej.status = 'pending' 
                           LIMIT 5"); // batch

    $stmt->execute();
    $jobs = $stmt->fetchAll();

    foreach ($jobs as $job) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $gmail_user;
            $mail->Password = $gmail_app_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('telecom@gmail.com', 'Admin');
            $mail->addAddress($job['email_professionnel']);
            $mail->Subject = $job['subject'];
            $mail->Body = $job['content'];
            $mail->isHTML(true);

            if (!empty($job['attachment_path']) && file_exists($job['attachment_path'])) {
                $mail->addAttachment($job['attachment_path']);
            }

            $mail->send();

            // mark as sent
            $update = $pdo->prepare("UPDATE email_jobs SET status = 'sent', sent_at = NOW() WHERE id = ?");
            $update->execute([$job['id']]);

        } catch (Exception $e) {
            $fail = $pdo->prepare("UPDATE email_jobs SET status = 'failed', error = ? WHERE id = ?");
            $fail->execute([$e->getMessage(), $job['id']]);
        }
    }

} catch (Exception $ex) {
    echo 'Worker error: ' . $ex->getMessage();
}
