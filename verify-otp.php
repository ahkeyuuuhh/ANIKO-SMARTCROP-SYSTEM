<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = $_POST['otp'];

    if (isset($_SESSION['pending_otp']) && $entered_otp == $_SESSION['pending_otp']) {
        // OTP is correct → finalize login
        $_SESSION['account_id'] = $_SESSION['pending_account_id'];
        
        // Clear OTP session
        unset($_SESSION['pending_otp']);
        unset($_SESSION['pending_account_id']);

        // Redirect to dashboard/testimonial page
        header("Location: testimonial-submit.php");
        exit();
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
</head>
<body>
    <h2>Enter OTP</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="otp" placeholder="Enter 6-digit code" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
