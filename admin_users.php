<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'CONFIG/config.php';
include 'INCLUDE/admin_header.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'delete_user' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM accounts WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "User deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting user.";
    }
    $stmt->close();
    header("Location: admin_users.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] === 'delete_admin' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM admin_accounts WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Admin account deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting admin account.";
    }
    $stmt->close();
    header("Location: admin_users.php");
    exit();
}

$reg_message = "";
if (isset($_POST['register_admin'])) {
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($new_username) || empty($new_password) || empty($confirm_password)) {
        $reg_message = '<div class="alert alert-danger">All fields are required.</div>';
    } elseif ($new_password !== $confirm_password) {
        $reg_message = '<div class="alert alert-danger">Passwords do not match.</div>';
    } else {
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        $stmt = $con->prepare("INSERT INTO admin_accounts (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $new_username, $hashedPassword);
        try {
            $stmt->execute();
            $reg_message = '<div class="alert alert-success">New admin registered successfully!</div>';
        } catch (mysqli_sql_exception $e) {
            $reg_message = '<div class="alert alert-danger">Username already exists!</div>';
        }
        $stmt->close();
    }
}

$users = $con->query("SELECT id, name, email, created_at FROM accounts ORDER BY created_at DESC");

$admins = $con->query("SELECT id, username, created_at FROM admin_accounts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
     :root {
        --primary-green: #1D492C;
        --accent-green: #84cc16;
        --pastel-green: #BDE08A;
        --light-green: #f0fdf4;
        --dark-green: #143820;
        --dark-gray: #374151;
        --light-gray: #f9fafb;
        --white: #ffffff;
        --bg-color: #cfc4b2ff;
        --primary-brown: #8A6440;
        --dark-brown: #4D2D18;
        --gradient-primary: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        --gradient-secondary: linear-gradient(135deg, var(--primary-green), var(--pastel-green));
    }

    body {
        background-color: var(--bg-color) !important;
        min-height: 100vh;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        margin: 0;
    }

    .dashboard {
        margin-left: 280px;
        padding: 20px;
        transition: margin-left 0.3s ease;
        margin-top: 4rem;
    }
  
    @media (max-width: 992px) {
        .dashboard {
            margin-left: 0;
            padding: 15px;
        }
    }

    .card {
        background: var(--gradient-secondary);
        backdrop-filter: blur(12px) brightness(0.9);
        -webkit-backdrop-filter: blur(12px) brightness(0.9);
        border: 2px solid var(--dark-green);
        margin-bottom: 4rem !important;
        border-radius: 20px;
        padding: 20px 30px;
        border-radius: 0;
        border-top-right-radius: 80px;
        border-bottom-left-radius: 80px;
    }

    .card-body h5 {
        color: var(--light-green);
        margin-bottom: 1rem !important;
    }

    .header {
        color: var(--dark-brown);
        font-weight: bold;
    }

    .subheader {
        color: var(--primary-brown) !important;
        font-size: 18px;
        font-weight: 400;
    }

    .table {
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.15); 
        color: var(--white);
        border:none !important;
        border-collapse: collapse !important;
        width: 100%;
    }

    .table th {
         text-align: center;
        font-weight: 600;
        padding: 12px;
        background: rgba(0, 0, 0, 0.25) !important; 
        color: var(--light-green);
    }

    .table td {
        vertical-align: middle;
        padding: 10px;
        color: var(--primary-green) !important;
    }

    table td, 
    table th {
        border: 1px solid #ccc; 
    }

    table tr:first-child th {
        border-top: none; 
    }

    table tr:last-child td {
        border-bottom: none; 
    }

    table td:first-child,
    table th:first-child {
        border-left: none; 
    }

    table td:last-child,
    table th:last-child {
        border-right: none; 
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        transition: 0.3s ease;
    }

    .table thead tr  {
        background-color: var(--pastel-green);
        color: var(--dark-green) !important;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
        border-top: none !important;
    }

    .table .btn {
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 4px 10px;
        transition: all 0.3s ease;
    }

    .table .btn-success:hover {
        background-color: var(--dark-green) !important;
        color: var(--white);
    }

    .table .btn-danger {
        background: #dc2626;
        border: none;
    }

    .table .btn-danger:hover {
        background: #b91c1c;
    }

    .bi {
        margin-right: 1rem;
    }

    .bi-trash3-fill {
        margin-right: 7px;
    }

    .heading {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        justify-content: space-between !important;
    }

   .heading .col-6:last-child {
        display: flex;
        justify-content: flex-end; 
        align-items: center;
    }

    .heading a {
        background-color: var(--primary-brown) !important;
        padding: 10px 20px;
        border-radius: 20px;
        border: none !important; 
        font-weight: 500;
    }

   .heading a:hover {
        background-color: var(--dark-brown) !important;
        transition: all 0.3s ease-in-out;
    }


</style>

<body>
    <div class="dashboard">
        <div class="g-4">
            <div class="row heading">
                <div class="col-6">
                    <h1 class="mb-3 header">Manage Accounts</h1>
                    <h6 class="subheader">Central hub for managing all user credentials.</h6>
                </div>
                <div class="col-6">
                    <a role="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registerModal">
                        + Register New Admin
                    </a>
                </div>
            </div>
          

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <div class="card mb-4 shadow">
                <div class="card-body">
                    <h5 class="mb-0"><i class="bi bi-person-fill"></i>User Accounts</h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($users->num_rows > 0): ?>
                            <?php while ($row = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id']; ?></td>
                                    <td><?= htmlspecialchars($row['name']); ?></td>
                                    <td><?= htmlspecialchars($row['email']); ?></td>
                                    <td><?= $row['created_at']; ?></td>
                                    <td>
                                        <a href="admin_users.php?action=delete_user&id=<?= $row['id']; ?>" 
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this user?');"><i class="bi bi-trash3-fill"></i>Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No users found.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Register New Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?= $reg_message ?>
                        <form method="POST" action="">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="register_admin" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ✅ Admin Accounts Section -->
        <div class="card shadow">
            <div class="card-body">
                <h5 class="mb-0"><i class="bi bi-person-fill"></i>Admin Accounts</h5>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($admins->num_rows > 0): ?>
                        <?php while ($row = $admins->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td><?= $row['created_at']; ?></td>
                                <td>
                                    <a href="admin_users.php?action=delete_admin&id=<?= $row['id']; ?>" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this admin account?');"><i class="bi bi-trash3-fill"></i>Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No admin accounts found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

  
    

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
