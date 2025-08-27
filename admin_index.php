<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'CONFIG/config.php';
include 'INCLUDE/admin_header.php';

// ✅ Only allow logged-in admins
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- ✅ Important -->
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .dashboard {
            margin-left: 260px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 3px 8px rgba(0,0,0,0.15);
        }
        @media (max-width: 992px) {
            .dashboard {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2 class="mb-4">Welcome, Admin</h2>
        
        <!-- Quick Stats Section -->
        <div class="row g-4">
            <div class="col-sm-6 col-lg-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <?php
                        $res = $con->query("SELECT COUNT(*) AS total FROM accounts");
                        $users = $res->fetch_assoc()['total'] ?? 0;
                        ?>
                        <p class="display-6"><?= $users; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card text-center bg-dark text-white">
                    <div class="card-body">
                        <h5 class="card-title">Admin Accounts</h5>
                        <?php
                        $res = $con->query("SELECT COUNT(*) AS total FROM admin_accounts");
                        $admins = $res->fetch_assoc()['total'] ?? 0;
                        ?>
                        <p class="display-6"><?= $admins; ?></p>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Downloads</h5>
                        <p class="display-6">Coming Soon</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="card mt-5">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recently Registered Users</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent = $con->query("SELECT id, name, email, created_at FROM accounts ORDER BY created_at DESC LIMIT 5");
                        if ($recent->num_rows > 0):
                            while ($row = $recent->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= $row['created_at']; ?></td>
                            </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="4" class="text-center">No recent users.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
