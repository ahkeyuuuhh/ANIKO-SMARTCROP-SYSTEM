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

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['home_image'])) {
    $targetDir = "uploads/home/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES["home_image"]["name"]);
    $targetFilePath = $targetDir . time() . "_" . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES["home_image"]["tmp_name"], $targetFilePath)) {
            $stmt = $con->prepare("INSERT INTO home_images (file_path, uploaded_at) VALUES (?, NOW())");
            $stmt->bind_param("s", $targetFilePath);
            $stmt->execute();
            $stmt->close();
            $_SESSION['message'] = "Image uploaded successfully!";
        } else {
            $_SESSION['message'] = "Error uploading image.";
        }
    } else {
        $_SESSION['message'] = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
    }
    header("Location: admin_home.php");
    exit();
}

// Handle delete image
if (isset($_GET['action']) && $_GET['action'] === 'delete_home_image' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("SELECT file_path FROM home_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($filePath);
    $stmt->fetch();
    $stmt->close();

    if (!empty($filePath) && file_exists($filePath)) {
        unlink($filePath);
    }

    $stmt = $con->prepare("DELETE FROM home_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['message'] = "Image deleted successfully!";
    header("Location: admin_home.php");
    exit();
}

// Fetch home images
$images = $con->query("SELECT * FROM home_images ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Home Images</title>
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
        <div class="row g-4">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <!-- UPLOAD FORM TO -->
            <div class="col-sm-6 col-lg-12">
                <h2>Upload Home Image</h2>
                <form action="upload_home_image.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="file" name="home_image" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
            
        </div>
         <!-- UPLOADED IMAGES TABLE -->
        <h2>Uploaded Images</h2>
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
                $result = $con->query("SELECT * FROM home_images ORDER BY uploaded_at DESC");
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Image" style="width:150px;"></td>
                        <td><?= htmlspecialchars($row['image_path']) ?></td>
                        <td><?= htmlspecialchars($row['uploaded_at']) ?></td>
                        <td>
                            <a href="admin_index.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
   

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
