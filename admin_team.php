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

// Function to delete team member
function deleteTeam($id, $con) {
    $stmt = $con->prepare("SELECT image_path FROM team WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $found = $stmt->fetch();
    $stmt->close();

    if ($found && $imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }
    $stmt = $con->prepare("DELETE FROM team WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle delete action for team members
if (isset($_GET['delete_team_id'])) {
    deleteTeam(intval($_GET['delete_team_id']), $con);
    header("Location: admin_team.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Team Members</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <div class="container mt-5">
        <h2>Manage Team Members</h2>
        <form action="upload_team_member.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role / Subtext:</label>
                <input type="text" name="role" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Photo:</label>
                <input type="file" name="team_image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Team Member</button>
        </form>
    </div>

    <h2 class="mt-5">Current Team Members</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $con->query("SELECT * FROM team_members ORDER BY uploaded_at DESC");
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Team" style="width:80px; border-radius:50%;"></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <a href="admin_index.php?delete_team_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this team member?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
