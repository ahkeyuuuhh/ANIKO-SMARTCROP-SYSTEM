<?php
ob_start();
require __DIR__ . '/vendor/autoload.php';
session_start();

require 'CONFIG/config.php';

$client = new Google_Client();
$client->setClientId("67607885572-0unromtvovfl5bb73dmv8mb5shrop87n.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/ANIKO-SMARTCROP-SYSTEM/gClientSetup.php");
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

    $picture = preg_replace('/=s\d+-c$/', '=s80-c', $u->picture);

    $_SESSION['google_id'] = $u->id;
    $_SESSION['name']      = $u->name;
    $_SESSION['email']     = $u->email;
    $_SESSION['picture']   = $picture;

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

header("Location: testimonial-submit.php");
exit();


} else {
    $login_url = $client->createAuthUrl();
    header("Location: " . filter_var($login_url, FILTER_SANITIZE_URL));
    exit();
}
