<?php
error_reporting(E_ALL);
ini_set('display_errors', 1); //1 means true

//enable exception
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

//necessary objects in establishing connection
$host = '127.0.0.1'; //loop back IP address
$user = 'root';
$password = '';
$db = 'aniko_app'; //kung ano 'yung active schema/kung ano 'yung ginagamit


try {
    $con = new mysqli($host, $user, $password, $db);
}
catch(mysqli_sql_exception $e) //variable e represents the mysqli exceptions
{
    echo '<br> <div class = "alert alert-danger">Connection Failed!</div>'.$e->getMessage(); //-> symbol used to access the methods
}
?>