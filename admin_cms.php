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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin CMS</title>
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
        img.thumb { width: 120px; border-radius: 8px; }
    </style>
</head>
<body>
<div class="dashboard">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <h1>Admin CMS</h1>
    <ul class="nav nav-tabs" id="cmsTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#home">Home</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#benefits">Benefits</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#whyaniko">Why Aniko</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#team">Team</button></li>
    </ul>

    <div class="tab-content mt-3">
        <!-- Home Images -->
        <div class="tab-pane fade show active" id="home">
            <h2>Upload Home Image</h2>
            <form action="upload_home_image.php" method="POST" enctype="multipart/form-data" class="mb-3">
                <input type="file" name="home_image" class="form-control mb-2" required>
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </form>
            <h2>Uploaded Images</h2>
            <table class="table table-bordered">
                <thead><tr><th>Preview</th><th>File Path</th><th>Uploaded At</th><th>Action</th></tr></thead>
                <tbody>
                <?php
                $result = $con->query("SELECT * FROM home_images ORDER BY uploaded_at DESC");
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image_path']) ?>" style="width:150px;"></td>
                        <td><?= htmlspecialchars($row['image_path']) ?></td>
                        <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                        <td><a href="admin_home.php?action=delete_home_image&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Benefits Images -->
        <div class="tab-pane fade" id="benefits">
            <h2>Upload Benefits Image</h2>
            <form action="upload_benefits.php" method="POST" enctype="multipart/form-data" class="mb-3">
                <input type="file" name="benefits_image" class="form-control mb-2" required>
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </form>
            <h2>Uploaded Benefits Images</h2>
            <table class="table table-bordered">
                <thead><tr><th>Preview</th><th>File Path</th><th>Uploaded At</th><th>Action</th></tr></thead>
                <tbody>
                <?php
                $benefitsResult = $con->query("SELECT * FROM benefits_images ORDER BY uploaded_at DESC");
                while ($row = $benefitsResult->fetch_assoc()):
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image_path']) ?>" style="width:150px;"></td>
                        <td><?= htmlspecialchars($row['image_path']) ?></td>
                        <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                        <td><a href="admin_benefits.php?delete_benefits_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this benefits image?')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Why Aniko Images -->
        <div class="tab-pane fade" id="whyaniko">
            <h2>Upload Why Aniko Image</h2>
            <form action="upload_why_aniko.php" method="POST" enctype="multipart/form-data" class="mb-3">
                <input type="file" name="why_aniko_image" class="form-control mb-2" required>
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </form>
            <h2>Uploaded Why Aniko Images</h2>
            <table class="table table-bordered">
                <thead><tr><th>Preview</th><th>File Path</th><th>Uploaded At</th><th>Action</th></tr></thead>
                <tbody>
                <?php
                $result = $con->query("SELECT * FROM why_aniko_images ORDER BY uploaded_at DESC");
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image_path']) ?>" style="width:200px;"></td>
                        <td><?= htmlspecialchars($row['image_path']) ?></td>
                        <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                        <td><a href="admin_why_aniko.php?delete_why_aniko_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Team Members -->
        <div class="tab-pane fade" id="team">
            <h2>Manage Team Members</h2>
            <form action="upload_team_member.php" method="POST" enctype="multipart/form-data" class="mb-3">
                <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                <input type="text" name="role" class="form-control mb-2" placeholder="Role / Subtext" required>
                <input type="file" name="team_image" class="form-control mb-2" required>
                <button type="submit" class="btn btn-primary">Add Team Member</button>
            </form>
            <h2>Current Team Members</h2>
            <table class="table table-bordered">
                <thead><tr><th>Photo</th><th>Name</th><th>Role</th><th>Action</th></tr></thead>
                <tbody>
                <?php
                $result = $con->query("SELECT * FROM team_members ORDER BY uploaded_at DESC");
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image_path']) ?>" style="width:80px; border-radius:50%;"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><a href="admin_team.php?delete_team_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this team member?')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
