<?php
// Make sure nothing is output before headers
ob_start();

require __DIR__ . '/../vendor/autoload.php';
session_start();

include 'CONFIG/config.php'; // use your existing DB connection

// Google Client Setup
$client = new Google_Client();
$client->setClientId("914921820277-65g7cco12fl293e2o9u1v1kd1rdfcrmk.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/ANIKOWEB/ANIKO-SMARTCROP-SYSTEM/gClientSetup.php");
$client->addScope("email");
$client->addScope("profile");

// If Google returns a code
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        die("Error fetching token: " . htmlspecialchars($token['error_description']));
    }

    $client->setAccessToken($token);

    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    // Store user info in session
    $_SESSION['google_id'] = $google_account_info->id;
    $_SESSION['name']      = $google_account_info->name;
    $_SESSION['email']     = $google_account_info->email;
    $_SESSION['picture']   = $google_account_info->picture;

    // ==============================
    // SAVE TO DATABASE (accounts)
    // ==============================
    $google_id = $_SESSION['google_id'];
    $name      = $_SESSION['name'];
    $email     = $_SESSION['email'];

    // Check if account already exists
    $stmt = $con->prepare("SELECT id FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // If no account → insert
    if (!$user_id) {
        $stmt = $con->prepare("INSERT INTO accounts (name, email, google_id, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $google_id);
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();
    }

    // Save database user_id into session
    $_SESSION['user_id'] = $user_id;

    // Redirect to testimonial-submit.php
    header("Location: /ANIKOWEB/ANIKO-SMARTCROP-SYSTEM/testimonial-submit.php");
    exit();
} 
else {
    // No code yet, send user to Google login
    $login_url = $client->createAuthUrl();
    header("Location: " . filter_var($login_url, FILTER_SANITIZE_URL));
    exit();
}
