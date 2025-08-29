<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; // Load PHPMailer
include 'CONFIG/config.php';  // In case you need DB logging

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_id = $_POST['contact_id'];
    $to_email   = $_POST['to_email'];
    $subject    = $_POST['subject'];
    $reply_msg  = $_POST['reply_message'];

    // Setup PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings (adjust based on your email provider)
        $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'roldancchristian@gmail.com';       // your Gmail
            $mail->Password   = 'ihmd kpcp njeu lnfs';         // Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

$mail->setFrom('roldancchristian@gmail.com', 'Admin');


        // Sender and recipient
        $mail->setFrom('roldancchristian@gmail.com', 'ANIKO');
        $mail->addAddress($to_email);   

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br(htmlspecialchars($reply_msg));
        $mail->AltBody = strip_tags($reply_msg);

        $mail->send();

        $_SESSION['message'] = "Reply sent successfully to $to_email!";
    } catch (Exception $e) {
        $_SESSION['message'] = "Reply failed. Error: {$mail->ErrorInfo}";
    }

    header("Location: admin_contact.php");
    exit();
}
?>
