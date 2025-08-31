<?php
session_start();
include "CONFIG/config.php";

$message = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $message = '<div class="alert alert-danger">Please enter both username and password.</div>';
    } else {
        $stmt = $con->prepare("SELECT id, password FROM admin_accounts WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashedPassword);
            $stmt->fetch();
          


            if (password_verify($password, $hashedPassword)) {
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_username'] = $username;

                header("Location: admin_index.php");
                exit;
            } else {
                $message = '<div class="alert alert-danger">Invalid password.</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">No such admin account found.</div>';
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
    :root {
        --primary-green: #1D492C;
        --accent-green: #84cc16;
        --pastel-green: #BDE08A;
        --light-green: #f0fdf4;
        --dark-green: #143820;
        --primary-brown: #8A6440;
        --dark-brown: #4D2D18;
        --bg-color: #cfc4b2ff;
        --form-color: #cfc4b271;
        --gradient-primary: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        --gradient-secondary: linear-gradient(135deg, var(--primary-green), var(--pastel-green));
    }

    body {
        background: var(--gradient-secondary) !important;
        min-height: 100vh !important;
        background-size: cover; 
        background-repeat: no-repeat;
    }

    .card {
        width: 35%;
        padding: 50px !important;
        background-color: var(--light-green);
        border-radius: 0;
        border-top-right-radius: 80px !important;
        border-bottom-left-radius: 80px !important;
        border: 2px solid var(--primary-green);
        box-shadow: 0px 0px 20px 5px var(--pastel-green) !important;
    }

    form button {
        margin-top: 1rem;
    }

    form {
        margin-bottom: 1rem;
    }

    .logo {
        width: 12%;
        justify-content: center;
        align-items: center;
        display: block;
        margin: 0 auto !important;
        margin-bottom: 1rem !important;
    }

    .card h3 {
        color: var(--primary-green);
        font-weight: bold;
    }

    .form-label {
        color: var(--primary-brown);
        font-weight: 500;
    }

    .form-control {
        border: 2px solid var(--pastel-green); 
        color: var(--primary-green)
    }

    .form-control:hover {
        border: 2px solid var(--primary-green) !important;
        background-color: var(--form-color) !important;
    }

    .form-control:focus {
        border: 2px solid var(--dark-brown) !important;
    }

    button {
        background: var(--gradient-secondary) !important;
        border: none !important;
        border-radius: 20px !important;
        box-shadow: 1px 1px 5px 2px #00000042 !important;
        color: var(--light-green) !important;
        font-weight: 500 !important;
    }

    button:hover {
        background:  var(--gradient-primary) !important;
    }

</style>

<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow">
        <img src="IMG/logo-noText.png" alt="logo" class="logo">
        <h3 class="text-center mb-3">Admin Login</h3>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter username"required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter password"required>
            </div>

            <?= $message ?>
            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>
