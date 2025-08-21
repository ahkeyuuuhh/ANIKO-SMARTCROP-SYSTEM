<?php
include 'CONFIG/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['benefits_image'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $fileName = time() . "_" . basename($_FILES['benefits_image']['name']);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['benefits_image']['tmp_name'], $targetFilePath)) {
        $stmt = $con->prepare("INSERT INTO benefits_images (image_path) VALUES (?)");
        $stmt->bind_param("s", $targetFilePath);
        $stmt->execute();

        header("Location: admin_index.php?success=1");
        exit();
    } else {
        header("Location: admin_index.php?error=upload_failed");
        exit();
    }
}
?>
