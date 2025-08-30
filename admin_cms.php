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
    :root {
        --primary-green: #1D492C;
        --accent-green: #84cc16;
        --pastel-green: #BDE08A;
        --light-green: #f0fdf4;
        --dark-green: #143820;
        --dark-gray: #374151;
        --light-gray: #f9fafb;
        --white: #ffffff;
        --bg-color: #cfc4b2ff;
        --primary-brown: #8A6440;
        --dark-brown: #4D2D18;
        --gradient-primary: linear-gradient(135deg, var(--primary-green), var(--accent-green));
        --gradient-secondary: linear-gradient(135deg, var(--primary-green), var(--pastel-green));
    }

    body {
        background-color: var(--bg-color) !important;
        min-height: 100vh;
    }

    .dashboard {
        margin-left: 280px;
        padding: 20px;
        transition: margin-left 0.3s ease;
        margin-top: 4rem;
    }
    @media (max-width: 992px) {
        .dashboard {
            margin-left: 0;
            padding: 15px;
        }
    }

    img.thumb { 
        width: 120px; border-radius: 8px; 
    }

    .header {
        color: var(--dark-brown);
        font-weight: bold;
        text-align:center;
    }

    .subheader {
        font-size: 18px;
        font-weight: 400;
        color: var(--primary-brown);
        text-align:center;
        margin-bottom: 1rem;
    }


    .nav-tabs {
        border-radius: 20px;
        width: 30%;
        justify-content: center;
        align-items: center;
        margin: 0 auto !important;
        border: none !important;
        background-color: var(--pastel-green);
        backdrop-filter: blur(12px) brightness(0.9);
        -webkit-backdrop-filter: blur(12px) brightness(0.9);
        padding: 0;
        margin-top: 2rem !important;
        margin-bottom: 3rem !important;
    }

    .nav-link {
        color: var(--primary-green);
        text-decoration: none;
        font-weight: 500 !important;
        align-items: center !important;
        display: flex; 
    }

    .nav-link:hover {
        background-color: #1d492c65;
        color: white;
        text-decoration: none;
        font-weight: 500;
        border: none;
        border-radius: 20px;
    }

    .nav-link.active {
        background-color: var(--primary-brown) !important;
        border: none !important;
        border-radius: 20px;
        color: var(--light-green) !important;
        font-weight: bold !important;
        margin: 0;
        left: 0 !important;
    }

    .nav-tabs .nav-item:first-child .nav-link.active {
       margin-left: -10px !important;
    }

     .nav-tabs .nav-item:last-child .nav-link.active {
       margin-right: -10px !important;
    }

    .card {
        background: var(--gradient-secondary) !important;
        backdrop-filter: blur(12px) brightness(0.9);
        -webkit-backdrop-filter: blur(12px) brightness(0.9);
        padding: 20px 30px !important;
        border: 2px solid var(--dark-green) !important;
        margin-bottom: 2rem;
        border-radius: 0px;
        border-top-right-radius: 80px;
        border-bottom-left-radius: 80px;
    }

    .card h5 {
        color: var(--light-green) !important;
        margin-bottom: 1rem;
    }

    form {
        display: flex;
        align-items: center;
    }

    form button {
        justify-content: space-between;
        margin-left: auto;
        background-color: transparent !important;
        border: 2px solid var(--primary-brown) !important;
        color: var(--primary-brown) !important;
        font-weight: 500 !important;
    }

    form button:hover {
        background-color: var(--pastel-green) !important;
        color: var(--primary-green) !important;
    }

    input {
        width: 88% !important;
    }
    
    .bi {
        margin-right: 1rem;
    }
    
    table {
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.15); 
        color: var(--white);
    }

    .table th {
        text-align: center;
        font-weight: 600;
        padding: 12px;
        background: rgba(0, 0, 0, 0.25) !important; 
        color: var(--light-green);
        border-top: none !important;
    }

    .table td {
        vertical-align: middle;
        padding: 10px;
        color: var(--primary-green) !important;
    }

    table td, 
    table th {
        border: 1px solid #ccc; 
    }

    table tr:first-child th {
        border-top: none; 
    }

    table tr:last-child td {
        border-bottom: none; 
    }

    table td:first-child,
    table th:first-child {
        border-left: none; 
    }

    table td:last-child,
    table th:last-child {
        border-right: none; 
    }

    .table tbody tr:hover {
        background: rgba(255, 255, 255, 0.08) !important;
        transition: 0.3s ease;
    }

    .table thead tr  {
        background: linear-gradient(135deg, #16a34a, #166534) !important;
        color: var(--dark-green) !important;
        border-top-right-radius: 20px;
        border-top-left-radius: 20px;
        border-top: none !important;
    }

    .team-fc {
        display: block;
    }

    .team-fc input {
        width: 100% !important;
    }

    .add-btn {
        margin-top: 1rem;
        justify-content: space-between;
        margin-left: auto;
        display: flex;
    }

    .bi-trash3-fill {
        margin-right: 7px !important;
    }

    .btn-danger {
        background-color: #b91c1c;
        border-radius: 10px;
    }

    .btn-danger:hover {
        background-color: #890c0cff;
    }


</style>
</head>
<body>
<div class="dashboard">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <h1 class="header">Content Management</h1>
    <h6 class="subheader">Create, update, and organize website content.</h6>
    
    <ul class="nav nav-tabs" id="cmsTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#home">Home</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#benefits">Benefits</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#whyaniko">Why Aniko</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#team">Team</button></li>
    </ul>

    <div class="tab-content mt-3">
        <!-- Home Images -->
        <div class="tab-pane fade show active" id="home">
            <div class="card">
                <h5><i class="bi bi-box-arrow-in-down"></i>Upload Home Image</h5>
                <form action="upload_home_image.php" method="POST" enctype="multipart/form-data" class="mb-3">
                    <input type="file" name="home_image" class="form-control" required>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
           

            <div class="card">
                <h5><i class="bi bi-card-image"></i>Uploaded Images</h5>
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
                            <td><a href="admin_home.php?action=delete_home_image&id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">
                                <i class="bi bi-trash3-fill"></i>Delete
                            </a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

           
        </div>

        <!-- Benefits Images -->
        <div class="tab-pane fade" id="benefits">
            <div class="card">
                <h5><i class="bi bi-box-arrow-in-down"></i>Upload Benefits Image</h5>
                <form action="upload_benefits.php" method="POST" enctype="multipart/form-data" class="mb-3">
                    <input type="file" name="benefits_image" class="form-control" required>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
           
            <div class="card">
                <h5><i class="bi bi-image"></i>Uploaded Benefits Images</h5>
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
                            <td><a href="admin_benefits.php?delete_benefits_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this benefits image?')">
                                <i class="bi bi-trash3-fill"></i>Delete
                            </a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

           
        </div>

        <!-- Why Aniko Images -->
        <div class="tab-pane fade" id="whyaniko">
            <div class="card">
                <h5><i class="bi bi-box-arrow-in-down"></i>Upload Why Aniko Image</h5>
                <form action="upload_why_aniko.php" method="POST" enctype="multipart/form-data" class="mb-3">
                    <input type="file" name="why_aniko_image" class="form-control" required>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
           
            <div class="card">
                <h5><i class="bi bi-image"></i>Uploaded Why Aniko Images</h5>
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
                            <td><a href="admin_why_aniko.php?delete_why_aniko_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this image?')">
                                <i class="bi bi-trash3-fill"></i>Delete
                            </a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
        </div>

        <!-- Team Members -->
        <div class="tab-pane fade" id="team">
            <div class="card">
                <h5><i class="bi bi-box-arrow-in-down"></i>Manage Team Members</h5>
                <form action="upload_team_member.php" method="POST" enctype="multipart/form-data" class="team-fc mb-3">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
                    <input type="text" name="role" class="form-control mb-2" placeholder="Role / Subtext" required>
                    <input type="file" name="team_image" class="form-control mb-2" required>
                    <button type="submit" class="btn btn-primary add-btn">Add Team Member</button>
                </form>
            </div>
           
            <div class="card">
                <h5><i class="bi bi-image"></i>Current Team Members</h5>
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
                            <td><a href="admin_team.php?delete_team_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this team member?')"><i class="bi bi-trash3-fill"></i>Delete
                        </a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
           
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
