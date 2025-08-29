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

// ✅ Handle deleting normal users
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

// ✅ Handle deleting admin accounts
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
      --gradient-earthy: linear-gradient(135deg, var(--primary-brown), var(--primary-green));
    }

    body {
        background: var(--gradient-primary) !important;
        min-height: 100vh;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        margin: 0;
    }

    .dashboard {
        margin-left: 280px;
        padding: 20px;
        transition: margin-left 0.3s ease;
    }
  
    @media (max-width: 992px) {
        .dashboard {
            margin-left: 0;
            padding: 15px;
        }
    }

    .card {
        background: rgba(20, 56, 32, 0.55); 
        backdrop-filter: blur(12px) brightness(0.9);
        -webkit-backdrop-filter: blur(12px) brightness(0.9);
        border: none !important;
        box-shadow: 0px 0px 20px 5px var(--pastel-green) !important;
        margin-bottom: 4rem !important;
        border-radius: 20px;
    }

    .card-body h5 {
        color: var(--light-green);
        margin-bottom: 1rem !important;
    }

    .table {
        border-radius: 20px !important;
    }
</style>

<body>
    <div class="dashboard">
        <div class="g-4">
            <h2 class="mb-3">Manage Accounts</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <!-- ✅ Users Section -->
            <div class="card mb-4 shadow">
                <div class="card-body">
                    <h5 class="mb-0">User Accounts</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
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
                                        onclick="return confirm('Delete this user?');">Delete</a>
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
                <h5 class="mb-0">Admin Accounts</h5>
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
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
                                    onclick="return confirm('Delete this admin account?');">Delete</a>
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
         <div class="container my-4">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#registerModal">
                    + Register New Admin
                </button>
            </div>
    </div>
    

  
    

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
