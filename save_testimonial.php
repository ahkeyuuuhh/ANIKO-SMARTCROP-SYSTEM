<?php
session_start();
include 'CONFIG/config.php';

// ✅ Use account_id instead of user_id
if (!isset($_SESSION['account_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $testimonial = trim($_POST['testimonial']);
    $user_id = $_SESSION['account_id']; // ✅ correct session variable

    if (!empty($testimonial)) {
        $stmt = $con->prepare("
            INSERT INTO testimonials (user_id, testimonial, status, created_at, updated_at)
            VALUES (?, ?, 'pending', NOW(), NOW())
        ");
        if (!$stmt) {
            die("Prepare failed: " . $con->error);
        }
        $stmt->bind_param("is", $user_id, $testimonial);

        if ($stmt->execute()) {
            header("Location: testimonial-submit.php?success=1");
            exit();
        } else {
            echo "❌ Error saving testimonial: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "❌ Testimonial cannot be empty.";
    }
}

$con->close();
?>
