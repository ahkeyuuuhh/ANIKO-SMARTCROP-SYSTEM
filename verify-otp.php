<?php
session_start();
require 'CONFIG/config.php';

// Check if OTP was generated
if (!isset($_SESSION['pending_otp']) || !isset($_SESSION['pending_account_id'])) {
    header("Location: index.php");
    exit();
}

// Handle OTP submit
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enteredOtp = trim($_POST['otp']);

    // Expired after 5 minutes
    if (time() - $_SESSION['otp_time'] > 300) {
        $error = "Your OTP has expired. Please log in again.";
        session_destroy();
    } elseif ($enteredOtp == $_SESSION['pending_otp']) {
        // OTP valid → mark user as logged in
        $_SESSION['account_id'] = $_SESSION['pending_account_id'];

        unset($_SESSION['pending_otp'], $_SESSION['pending_account_id'], $_SESSION['otp_time']);

        $redirectPage = $_SESSION['login_redirect'] ?? 'testimonial-submit.php';
        unset($_SESSION['login_redirect']);

        header("Location: " . $redirectPage);
        exit();
    } else {
        $error = "Invalid OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Verify OTP - Google</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <style>
    body {
      background: #f1f1f1;
      font-family: 'Roboto', sans-serif;
    }
    .container {
      max-width: 400px;
      margin: 60px auto;
      background: #fff;
      padding: 40px 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      text-align: center;
    }
    .google-logo {
      width: 80px;
      margin-bottom: 20px;
    }
    h2 {
      font-weight: normal;
      margin-bottom: 10px;
    }
    p {
      color: #555;
      font-size: 14px;
      margin-bottom: 20px;
    }
    input[type="text"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-bottom: 15px;
      font-size: 16px;
      text-align: center;
      letter-spacing: 4px;
    }
    .btn {
      width: 100%;
      background: #1a73e8;
      border: none;
      padding: 12px;
      font-size: 16px;
      border-radius: 5px;
      color: #fff;
      cursor: pointer;
      transition: background 0.2s ease;
    }
    .btn:hover {
      background: #1666c1;
    }
    .error {
      color: red;
      margin-bottom: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google Logo" class="google-logo">
    <h2>Verify it’s you</h2>
    <p>We sent a 6-digit code to your email<br><b><?php echo htmlspecialchars($_SESSION['email']); ?></b></p>

    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
      <input type="text" name="otp" maxlength="6" placeholder="Enter code" required>
      <button type="submit" class="btn">Verify</button>
    </form>
  </div>
</body>
</html>
