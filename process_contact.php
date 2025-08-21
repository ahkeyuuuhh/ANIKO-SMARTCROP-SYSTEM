<?php
session_start();
require_once "CONFIG/config.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName  = trim($_POST['firstName']);
    $lastName   = trim($_POST['lastName']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $subject    = trim($_POST['subject']);
    $message    = trim($_POST['message']);
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;

    try {
        $stmt = $con->prepare("INSERT INTO contact_messages 
            (first_name, last_name, email, phone, subject, message, newsletter) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $phone, $subject, $message, $newsletter);
        $stmt->execute();

        $_SESSION['success'] = "Your message has been sent successfully!";
        header("Location: compliance.php"); 
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Something went wrong: " . $e->getMessage();
        header("Location: compliance.php");
        exit;
    }
}
?>
