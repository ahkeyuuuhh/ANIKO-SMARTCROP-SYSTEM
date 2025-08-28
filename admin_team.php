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

// Function to delete team member (use the correct table: team_members)
function deleteTeam($id, $con) {
    // Get image path
    $stmt = $con->prepare("SELECT image_path FROM team_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $found = $stmt->fetch();
    $stmt->close();

    // Remove image file if found
    if ($found && $imagePath && file_exists($imagePath)) {
        @unlink($imagePath);
    }

    // Delete record
    $stmt = $con->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle delete action for team members (make sure the link hits THIS page)
if (isset($_GET['delete_team_id'])) {
    deleteTeam((int)$_GET['delete_team_id'], $con);
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
    <style>
        .dashboard {
            margin-left: 260px;
            padding: 20px;
            transition: margin-left 0.3s ease;
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
        <div class="g-4">
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
                            <!-- Point to THIS page so the handler runs -->
                            <a href="admin_team.php?delete_team_id=<?= (int)$row['id'] ?>" 
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete this team member?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
