<?php
include 'CONFIG/config.php';

if (isset($_FILES['why_aniko_image'])) {
    $targetDir = "IMG/";
    $fileName = time() . "_" . basename($_FILES["why_aniko_image"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["why_aniko_image"]["tmp_name"], $targetFile)) {
        $stmt = $con->prepare("INSERT INTO why_aniko_images (image_path) VALUES (?)");
        $stmt->bind_param("s", $targetFile);
        $stmt->execute();
        header("Location: admin_index.php?upload_success=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error uploading file.</div>";
    }
}
?>
