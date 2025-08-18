<?php
ob_start();
require __DIR__ . '/../vendor/autoload.php';
session_start();

require 'CONFIG/config.php'; // <-- add this to use $con

$client = new Google_Client();
$client->setClientId("914921820277-65g7cco12fl293e2o9u1v1kd1rdfcrmk.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/ANIKOWEB/ANIKO-SMARTCROP-SYSTEM/gClientSetup.php");
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        die("Error fetching token: " . htmlspecialchars($token['error_description']));
    }

    $client->setAccessToken($token);
    $google_oauth = new Google_Service_Oauth2($client);
    $u = $google_oauth->userinfo->get();

    // Normalize size of the picture URL (optional)
    $picture = preg_replace('/=s\d+-c$/', '=s80-c', $u->picture);

    // Save to session
    $_SESSION['google_id'] = $u->id;
    $_SESSION['name']      = $u->name;
    $_SESSION['email']     = $u->email;
    $_SESSION['picture']   = $picture;

    // Upsert into accounts (by email)
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

    header("Location: /ANIKOWEB/ANIKO-SMARTCROP-SYSTEM/testimonial-submit.php");
    exit();
} else {
    $login_url = $client->createAuthUrl();
    header("Location: " . filter_var($login_url, FILTER_SANITIZE_URL));
    exit();
}
