<?php
session_start();
include 'CONFIG/config.php'; // DB connection

// Optional: restrict to admin only
// if (!isset($_SESSION['admin'])) {
//     header("Location: login.php");
//     exit();
// }

// --- Handle Approve ---
if (isset($_GET['action']) && $_GET['action'] === 'approve' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("UPDATE testimonials SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Testimonial approved successfully!";
    } else {
        $_SESSION['message'] = "Error approving testimonial.";
    }
    $stmt->close();
    header("Location: admin_index.php");
    exit();
}

// --- Handle Delete Testimonial ---
if (isset($_GET['action']) && $_GET['action'] === 'delete_testimonial' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Testimonial deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting testimonial.";
    }
    $stmt->close();
    header("Location: admin_index.php");
    exit();
}

// --- Handle Delete Account ---
if (isset($_GET['action']) && $_GET['action'] === 'delete_account' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("DELETE FROM accounts WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Account deleted successfully!";
    } else {
        $_SESSION['message'] = "Error deleting account.";
    }
    $stmt->close();
    header("Location: admin_index.php");
    exit();
}

// --- Fetch Testimonials ---
$pending = $con->query("SELECT t.id, a.name, a.email, t.testimonial, t.created_at 
                        FROM testimonials t 
                        JOIN accounts a ON t.user_id = a.id 
                        WHERE t.status = 'pending'
                        ORDER BY t.created_at DESC");

$approved = $con->query("SELECT t.id, a.name, a.email, t.testimonial, t.created_at 
                         FROM testimonials t 
                         JOIN accounts a ON t.user_id = a.id 
                         WHERE t.status = 'approved'
                         ORDER BY t.created_at DESC");

// --- Fetch Accounts ---
$accounts = $con->query("SELECT id, name, email, created_at FROM accounts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Testimonials & Accounts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h2>Admin Dashboard</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info">
            <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); 
            ?>
        </div>
    <?php endif; ?>

    <!-- Pending Testimonials -->
    <h3 class="mt-4">Pending Testimonials</h3>
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
        <?php while ($row = $pending->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['testimonial']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="admin_index.php?action=approve&id=<?php echo $row['id']; ?>" 
                       class="btn btn-sm btn-success"
                       onclick="return confirm('Approve this testimonial?');">Approve</a>
                    <a href="admin_index.php?action=delete_testimonial&id=<?php echo $row['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this testimonial?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Approved Testimonials -->
    <h3 class="mt-5">Approved Testimonials</h3>
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
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['testimonial']); ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td>
                    <a href="admin_index.php?action=delete_testimonial&id=<?php echo $row['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Delete this testimonial?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Accounts Table -->
    <h3 class="mt-5">Accounts</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($acc = $accounts->fetch_assoc()): ?>
            <tr>
                <td><?php echo $acc['id']; ?></td>
                <td><?php echo htmlspecialchars($acc['name']); ?></td>
                <td><?php echo htmlspecialchars($acc['email']); ?></td>
                <td><?php echo $acc['created_at']; ?></td>
                <td>
                    <a href="admin_index.php?action=delete_account&id=<?php echo $acc['id']; ?>" 
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('⚠️ WARNING: This will permanently delete the account and their testimonials. Continue?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
