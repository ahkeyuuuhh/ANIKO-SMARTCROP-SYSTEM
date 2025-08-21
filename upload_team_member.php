<?php
include 'CONFIG/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $role = $con->real_escape_string($_POST['role']);

    if (isset($_FILES['team_image']) && $_FILES['team_image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/team/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $filename = time() . "_" . basename($_FILES['team_image']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['team_image']['tmp_name'], $targetFile)) {
            $stmt = $con->prepare("INSERT INTO team_members (name, role, image_path) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $role, $targetFile);
            $stmt->execute();
            header("Location: admin_index.php");
            exit;
        } else {
            echo "Error uploading file.";
        }
    }
}
?>
