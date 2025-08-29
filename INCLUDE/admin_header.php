
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

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
      --gradient-earthy: linear-gradient(135deg, var(--primary-brown), var(--primary-green));
    }

    body {
      margin: 0;
      padding: 0;
      background: var(--bg-color) !important;
      font-family: Arial, sans-serif;
    }

    /* Sidebar styles */
    .sidebar {
      height: 90%;
      width: 270px;
      position: fixed;
      left: 10px;
      top: 50px;
      background: var(--dark-green) !important;
      color: var(--light-green);
      border-radius: 20px;
      padding-top: 20px;
      transition: all 0.3s;
      z-index: 1000;
      display: flex;
      flex-direction: column;
    }

    .sidebar .nav-link {
      color: var(--light-green);
      padding: 12px 20px;
      display: block;
      font-weight: 500;
      border-radius: 12px;
      transition: background 0.3s, color 0.3s;
    }

    .sidebar .nav-link:hover {
      background: var(--primary-green) !important;
      color: var(--c2);
    }

    .nav-link i {
      margin-right: 1rem;
    }

    .sidebar .sidebar-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar .sidebar-header img {
      height: 60px;
      margin-bottom: 10px;
    }

    .content {
      margin-left: 290px;
      padding: 20px;
    }

    .logout {
      margin-top: auto;
      font-weight: bold !important;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
      .sidebar {
        width: 200px;
      }
      .content {
        margin-left: 220px;
      }
    }
  </style>
</head>
<body>

<div class="sidebar">
  <div class="sidebar-header">
    <img src="IMG/logo-noText.png" alt="Admin Logo">
    <h5>Admin Panel</h5>
    <?php if(isset($_SESSION['name'])): ?>
      <p>Welcome, Admin</p>
    <?php endif; ?>
  </div>
    <a class="nav-link" href="admin_index.php"><i class="bi bi-house-door-fill"></i>Home</a>
    <a class="nav-link" href="admin_testimonial.php"><i class="bi bi-chat-left-dots-fill"></i>Testimonial Management</a>
    <a class="nav-link" href="admin_users.php"><i class="bi bi-person-lines-fill"></i>Account Management</a>
    <a class="nav-link" href="admin_contact.php"><i class="bi bi-chat-square-text-fill"></i>Contact Management</a>
    <a class="nav-link" href="admin_cms.php">Content Management</a>
   
    <a class="nav-link text-danger logout" href="admin_logout.php"><i class="bi bi-box-arrow-left"></i>Logout</a>
  </nav>
</div>