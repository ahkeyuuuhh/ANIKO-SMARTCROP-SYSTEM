<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php'; 
include 'CONFIG/config.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contact_id = $_POST['contact_id'];
    $to_email   = $_POST['to_email'];
    $subject    = $_POST['subject'];
    $reply_msg  = $_POST['reply_message'];

  
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'roldancchristian@gmail.com';     
            $mail->Password   = 'ihmd kpcp njeu lnfs';       
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

$mail->setFrom('roldancchristian@gmail.com', 'Admin');


     
        $mail->setFrom('roldancchristian@gmail.com', 'ANIKO');
        $mail->addAddress($to_email);   

      
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
