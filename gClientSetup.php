<?php
require __DIR__ . '/../vendor/autoload.php';
session_start();


$client = new Google_Client();
$client->setClientId("");
$client->setClientSecret("");
$client->setRedirectUri("http://localhost/AnikoWebsite/Aniko/gClientSetup.php"); // ✅ same as Google Console
$client->addScope("email");
$client->addScope("profile");


if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (!isset($token["error"])) {
        $client->setAccessToken($token['access_token']);

      
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

      
        $_SESSION['user_id']   = $google_account_info->id;
        $_SESSION['name']      = $google_account_info->name;
        $_SESSION['email']     = $google_account_info->email;
        $_SESSION['picture']   = $google_account_info->picture;

        
        header("Location: testimonial-submit.php");
        exit();
    } else {
        echo "Login failed: " . htmlspecialchars($token["error"]);
    }
} else {
   
    header("Location: testimonial-submit.php");
    exit();
}
