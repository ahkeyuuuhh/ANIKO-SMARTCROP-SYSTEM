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
        height: 100vh;
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
        color: var(--dark-green);
        font-weight: bold;
        text-shadow: 0px 0px 20px var(--pastel-green);
    }

    .header2 {
        color: var(--light-green);
        font-weight: 500;
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
                <h3 class="mt-4 header2">Pending Testimonials</h3>
                <table class="table table-bordered table-striped">
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
                                    <td>
                                        <a href="admin_testimonial.php?action=approve&id=<?= $row['id']; ?>" 
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Approve this testimonial?');">Approve</a>
                                        <a href="admin_testimonial.php?action=delete_testimonial&id=<?= $row['id']; ?>" 
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this testimonial?');">Delete</a>
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
        <!-- ✅ Approved Testimonials -->
        <h3 class="mt-5 header2">Approved Testimonials</h3>
        <table class="table table-bordered table-striped">
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
                    <td>
                        <a href="admin_testimonial.php?action=delete_testimonial&id=<?= $row['id']; ?>" 
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Delete this testimonial?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
