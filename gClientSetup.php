    <?php
   require __DIR__ . '/../vendor/autoload.php';

session_start();


$client = new Google_Client();
$client->setClientId("914921820277-65g7cco12fl293e2o9u1v1kd1rdfcrmk.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/ANIKOWEB/ANIKO-SMARTCROP-SYSTEM/gClientSetup.php"); // ✅ same as Google Console
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
