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
            height: 100vh;
        }

        .dashboard {
            margin-left: 280px;
            padding: 20px;
            transition: margin-left 0.3s ease;
            margin-top: 4rem !important;
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

        .dashboard h1 {
            font-weight: bold;
            color: var(--light-green);
            margin-bottom: 3rem !important;
            text-shadow: 0px 0px 10px var(--accent-green);
        }

        .count-card {
            background: rgba(0, 50, 0, 0.5) !important;
            backdrop-filter: blur(10px) brightness(0.8); 
            -webkit-backdrop-filter: blur(10px) brightness(0.8); 
            border: none !important;
            box-shadow: 0px 0px 20px 2px var(--pastel-green);
        }

        
        .table-card {
            background: rgba(20, 56, 32, 0.55); 
            backdrop-filter: blur(12px) brightness(0.9);
            -webkit-backdrop-filter: blur(12px) brightness(0.9);
            border-radius: 20px !important;
            overflow: hidden; 
            box-shadow: 0 4px 25px rgba(0,0,0,0.35);
            border: none !important;
        }

        h4 {
            color: var(--light-green);
            margin-bottom: 1rem;
        }

        .users-btn {
            background-color: var(--primary-brown);
            padding: 5px 20px;
            color: white;
            text-decoration: none;
            border-radius: 20px;
            font-weight: 500;
            align-items: center; 
        }

        .table {
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 20px;
            overflow: hidden;
            border: none !important;
        }

        .table thead th {
            background-color: var(--pastel-green);
            color: var(--primary-green);
            text-align: center;
            font-weight: 700 !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            border: none !important; 
        }

        .table tbody tr {
            background: rgba(72, 56, 56, 0.28) !important;
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(180, 255, 180, 0.15);
            transform: scale(1.01);
            box-shadow: inset 0px 0px 8px rgba(132, 204, 22, 0.4);
        }

        .table td {
            color: var(--primary-brown);
            text-align: center;
            padding: 12px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .table td:first-child {
            font-weight: bold;
            color: var(--dark-brown);
        }

        .table tbody tr td[colspan] {
            font-style: italic;
            color: var(--pastel-green);
            text-shadow: 0 0 6px rgba(255,255,255,0.2);
        }

        .display-6 {
            font-weight: 700;
            color: var(--light-green);
        }
        
        .bi {
            margin-right: 1rem;
        }

    </style>
</head>
<body>
    <div class="dashboard">
        <h1 class="mb-4">Welcome, Admin</h1>
        
        <!-- Quick Stats Section -->
        <div class="row g-4">
            <div class="col-sm-6 col-lg-4">
                <div class="card text-center bg-primary text-white count-card">
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
                <div class="card text-center bg-dark text-white count-card">
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
                <div class="card text-center bg-success text-white count-card">
                    <div class="card-body">
                        <h5 class="card-title">Downloads</h5>
                        <p class="display-6">Coming Soon</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Users Table -->
        <div class="card mt-5 table-card">
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-6">
                        <h4><i class="bi bi-person-fill-check"></i>Registered Accounts</h4>
                    </div>
                    <div class="col-6 text-end justify-content-between">
                        <a href="admin_users.php" role="button" class="users-btn">View Users</a>
                    </div>
                </div>
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
