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

                header("Location: admin_index1.php");
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
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-4" style="width: 400px;">
        <h3 class="text-center mb-3">Admin Login</h3>
        <?= $message ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>
