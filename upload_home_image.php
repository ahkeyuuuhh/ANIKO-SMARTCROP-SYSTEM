<?php
include 'CONFIG/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['home_image'])) {
    $targetDir = "IMG/";
    $fileName = time() . "_" . basename($_FILES['home_image']['name']);
    $targetFilePath = $targetDir . $fileName;

    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['home_image']['tmp_name'], $targetFilePath)) {
            $stmt = $con->prepare("INSERT INTO home_images (image_path) VALUES (?)");
            $stmt->bind_param("s", $targetFilePath);
            $stmt->execute();
            $stmt->close();

            header("Location: admin_index.php?upload_success=1");
            exit();
        } else {
            echo "Error uploading the file.";
        }
    } else {
        echo "Only JPG, JPEG, PNG, GIF files are allowed.";
    }
}
?>
