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

// Function to delete image
function deleteImage($table, $id, $con) {
    $stmt = $con->prepare("SELECT image_path FROM $table WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $found = $stmt->fetch();
    $stmt->close();

    if ($found && $imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }
    $stmt = $con->prepare("DELETE FROM $table WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Handle delete action for download images
if (isset($_GET['delete_download_id'])) {
    deleteImage('download_images', intval($_GET['delete_download_id']), $con);
    header("Location: admin_download.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Download Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

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

<body>
    <div class="dashboard">
        <div class="g-4">
            <h2>Upload Download Image</h2>
            <form action="upload_download_image.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="file" name="download_image" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Upload Image</button>
            </form>
            
            <h2 class="mt-5">Uploaded Download Images</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>File Path</th>
                        <th>Uploaded At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $con->query("SELECT * FROM download_images ORDER BY uploaded_at DESC");
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Download Image" style="width:200px;"></td>
                            <td><?= htmlspecialchars($row['image_path']) ?></td>
                            <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                            <td>
                                <a href="admin_download.php?delete_download_id=<?= $row['id'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this download image?')">Delete</a>
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
