<?php
ob_start();
require __DIR__ . '/vendor/autoload.php';
session_start();

require 'CONFIG/config.php';

// Google client setup
$client = new Google_Client();
$client->setClientId("67607885572-0unromtvovfl5bb73dmv8mb5shrop87n.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/ANIKO-SMARTCROP-SYSTEM/gClientSetup.php");
$client->addScope("email");
$client->addScope("profile");

// Save redirect
if (isset($_GET['redirect'])) {
    $_SESSION['login_redirect'] = $_GET['redirect'];
}

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        die("Error fetching token: " . htmlspecialchars($token['error_description']));
    }

    $client->setAccessToken($token);
    $google_oauth = new Google_Service_Oauth2($client);
    $u = $google_oauth->userinfo->get();

    $picture = preg_replace('/=s\d+-c$/', '=s80-c', $u->picture);

    $_SESSION['google_id'] = $u->id;
    $_SESSION['name']      = $u->name;
    $_SESSION['email']     = $u->email;
    $_SESSION['picture']   = $picture;

    // Check if user exists
    $stmt = $con->prepare("SELECT id FROM accounts WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $u->email);
    $stmt->execute();
    $stmt->bind_result($account_id);
    $exists = $stmt->fetch();
    $stmt->close();

    if ($exists) {
        $stmt = $con->prepare("UPDATE accounts SET name = ?, google_id = ?, picture = ? WHERE id = ?");
        $stmt->bind_param("sssi", $u->name, $u->id, $picture, $account_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['account_id'] = $account_id;
    } else {
        $stmt = $con->prepare("INSERT INTO accounts (name, email, google_id, picture) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $u->name, $u->email, $u->id, $picture);
        $stmt->execute();
        $_SESSION['account_id'] = $stmt->insert_id;
        $stmt->close();
    }

    // ✅ Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['pending_otp'] = $otp;
    $_SESSION['pending_account_id'] = $_SESSION['account_id'];
    $_SESSION['otp_time'] = time();

    // ✅ Send OTP via PHPMailer
    require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require 'vendor/phpmailer/phpmailer/src/SMTP.php';
    require 'vendor/phpmailer/phpmailer/src/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
        // SMTP settings (use your Gmail account for testing)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'roldancchristian@gmail.com'; // replace with your Gmail
        $mail->Password = 'ihmd kpcp njeu lnfs';  // use Google App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('roldancchristian@gmail.com', 'Aniko Smart Crop');
        $mail->addAddress($u->email, $u->name);

        $mail->isHTML(true);
        $mail->Subject = "Your OTP Code";
        $mail->Body = "Hello <b>{$u->name}</b>,<br><br>Your OTP code is: <b>{$otp}</b><br><br>This code will expire in 5 minutes.";

        $mail->send();
    } catch (Exception $e) {
        die("OTP email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }

    // ✅ Redirect to OTP page
    header("Location: verify-otp.php");
    exit();

} else {
    $login_url = $client->createAuthUrl();
    header("Location: " . filter_var($login_url, FILTER_SANITIZE_URL));
    exit();
}
