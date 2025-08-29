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

// ✅ Function to handle testimonial actions
function handleAction($action, $con) {
    if (isset($_GET['action']) && $_GET['action'] === $action && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($action === 'approve') {
            $stmt = $con->prepare("UPDATE testimonials SET status = 'approved' WHERE id = ?");
        } elseif ($action === 'delete_testimonial') {
            $stmt = $con->prepare("DELETE FROM testimonials WHERE id = ?");
        }
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = ucfirst($action) . "d successfully!";
        } else {
            $_SESSION['message'] = "Error " . $action . "ing.";
        }
        $stmt->close();
        header("Location: admin_testimonial.php");
        exit();
    }
}
handleAction('approve', $con);
handleAction('delete_testimonial', $con);

// ✅ Fetch testimonials
$pending = $con->query("
    SELECT t.id, a.name, a.email, t.testimonial, t.created_at 
    FROM testimonials t 
    JOIN accounts a ON t.user_id = a.id 
    WHERE t.status = 'pending'
    ORDER BY t.created_at DESC
");

$approved = $con->query("
    SELECT t.id, a.name, a.email, t.testimonial, t.created_at 
    FROM testimonials t 
    JOIN accounts a ON t.user_id = a.id 
    WHERE t.status = 'approved'
    ORDER BY t.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Testimonials</title>
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
        background-size: cover;
        background-repeat: no-repeat;
        max-height: 1000vh;
    }

    .dashboard {
        margin-left: 280px;
        padding: 20px;
        transition: margin-left 0.3s ease;
        margin-top: 4rem !important;
    }
  
    @media (max-width: 992px) {
        .dashboard {
            margin-left: 0;
            padding: 15px;
        }
    }

    .header {
        text-align: center;
        color: var(--light-green);
        font-weight: bold;
        text-shadow: 0px 0px 20px var(--accent-green);
        margin-bottom: 2rem;
    }

    .header2 {
        color: var(--light-green);
        font-weight: 500;
        margin-bottom: 1rem;
    }

    table {
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.15); 
        color: var(--white);
        border:none !important;
    }

    .approved-table th {
        text-align: center;
        font-weight: 600;
        padding: 12px;
        background: rgba(0, 0, 0, 0.25) !important; 
        color: var(--light-green);
    }

    .approved-table td {
        vertical-align: middle;
        padding: 10px;
        color: var(--primary-green) !important;
    }

    .pending-table th {
        text-align: center;
        font-weight: 600;
        padding: 12px;
        background: rgba(0, 0, 0, 0.25) !important; 
        color: var(--light-green);
    }

    .pending-table td {
        vertical-align: middle;
        padding: 10px;
        color: var(--primary-green) !important;
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.08);
        transition: 0.3s ease;
    }

    .pending-table thead {
        background: linear-gradient(135deg, #16a34a, #166534) !important;
        color: var(--dark-green) !important;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
    }

    .approved-table thead {
        background: linear-gradient(135deg, #16a34a, #166534) !important;
        border-top-right-radius: 20px !important; 
        border-top-left-radius: 20px !important;
    }

    .approved-table {
        border: none !important;
    }

    .table .btn {
        border-radius: 20px;
        font-size: 0.85rem;
        padding: 4px 10px;
        transition: all 0.3s ease;
    }

    .table .btn-success {
        background: var(--pastel-green);
        border: none;
        color: var(--dark-green);
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

    .card {
        background: rgba(20, 56, 32, 0.55); 
        backdrop-filter: blur(12px) brightness(0.9);
        -webkit-backdrop-filter: blur(12px) brightness(0.9);
        padding: 20px 30px !important;
        border: none !important;
        margin-bottom: 2rem;
        border-radius: 20px;
        box-shadow: 0px 0px 20px 4px var(--pastel-green);
    }

    .bi {
        margin-right: 1rem;
    }

    .bi-trash3-fill, .bi-check {
        margin-right: 7px !important;
    }

    .last-td {
        text-align: center;
    }
</style>

<body>
    <div class="dashboard">
        <div class="row- g-4">
            <div>
                <h1 class="header">Manage Testimonials</h1>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-info">
                        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                    </div>
                <?php endif; ?>

                <!-- ✅ Pending Testimonials -->
                <div class="card pending-card">
                    <h3 class="mt-2 header2"><i class="bi bi-clock-fill"></i>Pending Testimonials</h3>
                    <table class="table table-bordered table-striped pending-table">
                        <thead class="table-warning">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Testimonial</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($pending->num_rows > 0): ?>
                                <?php while ($row = $pending->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $row['id']; ?></td>
                                        <td><?= htmlspecialchars($row['name']); ?></td>
                                        <td><?= htmlspecialchars($row['email']); ?></td>
                                        <td><?= htmlspecialchars($row['testimonial']); ?></td>
                                        <td><?= $row['created_at']; ?></td>
                                        <td class="last-td">
                                            <a href="admin_testimonial.php?action=approve&id=<?= $row['id']; ?>" 
                                            class="btn btn-sm btn-success"
                                            onclick="return confirm('Approve this testimonial?');"><i class="bi bi-check"></i>  Approve</a>
                                            <a href="admin_testimonial.php?action=delete_testimonial&id=<?= $row['id']; ?>" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this testimonial?');"><i class="bi bi-trash3-fill"></i>Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        No pending testimonials found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
               
            </div>
        </div>
        <!-- ✅ Approved Testimonials -->
        <div class="card approved-card">
            <h3 class="mt-2 header2"><i class="bi bi-check-square-fill"></i>Approved Testimonials</h3>
            <table class="table table-bordered table-striped approved-table">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Testimonial</th>
                    <th>Approved At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $approved->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['testimonial']); ?></td>
                    <td><?= $row['created_at']; ?></td>
                    <td class="last-td">
                        <a href="admin_testimonial.php?action=delete_testimonial&id=<?= $row['id']; ?>" 
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this testimonial?');"><i class="bi bi-trash3-fill"></i>Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
       
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
