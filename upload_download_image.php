<?php
session_start();
include 'CONFIG/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['download_image'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = time() . "_" . basename($_FILES["download_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["download_image"]["tmp_name"], $targetFilePath)) {
        $stmt = $con->prepare("INSERT INTO download_images (image_path) VALUES (?)");
        $stmt->bind_param("s", $targetFilePath);
        $stmt->execute();
    }
}

header("Location: admin_index.php");
exit;
