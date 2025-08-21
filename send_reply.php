<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'CONFIG/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contactId = intval($_POST['contact_id'] ?? 0);
    $toEmail   = trim($_POST['to_email'] ?? '');
    $subject   = trim($_POST['subject'] ?? 'Re: Your Inquiry');
    $message   = trim($_POST['reply_message'] ?? '');

    if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid recipient email address.";
        header("Location: admin_index.php");
        exit();
    }

    if ($message === '') {
        $_SESSION['message'] = "Reply message cannot be empty.";
        header("Location: admin_index.php");
        exit();
    }

    try {
        $mail = new PHPMailer(true);
       
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'col.2023010024@lsb.edu.ph';      
        $mail->Password   = '';   
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // 
        $mail->Port       = 587;

        $mail->setFrom('col.2023010024@lsb.edu.ph', 'Admin Support');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br(htmlspecialchars($message));
        $mail->AltBody = $message;

        $mail->send();

        // (Optional) Save reply history
        // $stmt = $con->prepare("INSERT INTO contact_replies (contact_id, to_email, subject, message) VALUES (?, ?, ?, ?)");
        // $stmt->bind_param("isss", $contactId, $toEmail, $subject, $message);
        // $stmt->execute();
        // $stmt->close();

        $_SESSION['message'] = "Reply sent successfully!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Error sending reply: " . $mail->ErrorInfo;
    }

    header("Location: admin_index.php");
    exit();
} else {
    header("Location: admin_index.php");
    exit();
}
