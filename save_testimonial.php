<?php
session_start();
include 'CONFIG/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $testimonial = trim($_POST['testimonial']);
    $user_id = $_SESSION['user_id'];

    if (!empty($testimonial)) {
        $stmt = $con->prepare("INSERT INTO testimonials (user_id, testimonial, status, created_at, updated_at)
                               VALUES (?, ?, 'pending', NOW(), NOW())");
        $stmt->bind_param("is", $user_id, $testimonial);
        if ($stmt->execute()) {
            header("Location: testimonial-submit.php?success=1");
            exit();
        } else {
            echo "❌ Error saving testimonial: " . $con->error;
        }
        $stmt->close();
    } else {
        echo "❌ Testimonial cannot be empty.";
    }
}

$con->close();
?>
