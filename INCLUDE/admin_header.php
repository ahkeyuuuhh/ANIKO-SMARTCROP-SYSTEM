
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root {
      --c1: #CBBA9E;
      --c2: #BDE08A; 
      --c3: #8A6440; 
      --c4: #4D2D18;
      --c5: #112822; 
      --c6: #4C6444;
      --c7: #FFFFFF;
      --c8: #000000;
      --c9: #1D492C; 
    }

    body {
      margin: 0;
      padding: 0;
      background: var(--c1);
      font-family: Arial, sans-serif;
    }

    /* Sidebar styles */
    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      top: 20px;
      left: 20px;
      background: var(--c5);
      color: var(--c7);
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      padding-top: 20px;
      transition: all 0.3s;
      z-index: 1000;
    }

    .sidebar .nav-link {
      color: var(--c7);
      padding: 12px 20px;
      display: block;
      font-weight: 500;
      border-radius: 12px;
      transition: background 0.3s, color 0.3s;
    }

    .sidebar .nav-link:hover {
      background: var(--c6);
      color: var(--c2);
    }

    .sidebar .sidebar-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .sidebar .sidebar-header img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      margin-bottom: 10px;
    }

    .content {
      margin-left: 290px;
      padding: 20px;
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
    <img src="IMG/logo-hr.png" alt="Admin Logo">
    <h5>Admin Panel</h5>
    <?php if(isset($_SESSION['name'])): ?>
      <p>Welcome, Admin</p>
    <?php endif; ?>
  </div>
  <nav class="nav flex-column">
     <a class="nav-link" href="admin_index1.php">Home</a>
    <a class="nav-link" href="admin_testimonial.php">Testimonial Management</a>
    <a class="nav-link" href="admin_users.php">Account Management</a>
    <a class="nav-link" href="admin_contact.php">Contact Management</a>
    <a class="nav-link" href="admin_home.php">Home Management</a>
    <a class="nav-link" href="admin_benefits.php">Benefit Management</a>
      <a class="nav-link" href="admin_download.php">Download Management</a>
        <a class="nav-link" href="admin_whyaniko.php">Why Aniko Management</a>
          <a class="nav-link" href="admin_team.php">Team Members </a>
    <a class="nav-link text-danger" href="admin_logout.php">Logout</a>
  </nav>
</div>