
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
      --gradient-secondary: linear-gradient(135deg, var(--primary-green), var(--pastel-green));
    }

    body {
      margin: 0;
      padding: 0;
      background: var(--bg-color) !important;
      font-family: Arial, sans-serif;
    }

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

   
    .modal-content {
        background: var(--light-gray);
        color: var(--dark-brown);
        border-radius: 20px !important;
        border: 2px solid var(--dark-brown) !important;
        box-shadow: 0 0 15px rgba(29, 73, 44, 0.4);
    }

      .modal-header {
        border-bottom: 1px solid var(--primary-brown);
        background: var(--dark-brown);
        color: var(--white);
        font-weight: 600;
        letter-spacing: 1px;
        padding: 10px 15px!important;
        border-top: none !important;
        border-top-right-radius: 18px !important;
        border-top-left-radius: 18px !important
      }

      .modal-title {
        font-size: 1.2rem;
        font-weight: 600;
      }

      .modal-body {
        font-size: 1rem;
        color: var(--primary-green);
        text-align: center;
        font-weight: 500 !important;
      }

      .modal-footer {
        border-top: 1px solid var(--primary-brown);
        justify-content: center;
      }

      .modal-footer .btn-secondary {
        background: var(--pastel-green);
        border: none;
        color: var(--primary-green);
        transition: 0.3s ease;
        font-weight: bold;
      }

      .modal-footer .btn-secondary:hover {
        background: var(--primary-green);
      }

      .modal-footer .btn-danger {
        background: var(--primary-brown);
        border: none;
        font-weight: bold;
        color: var(--white);
        transition: 0.3s ease;
        box-shadow: 0 0 8px rgba(138, 100, 64, 0.7);
      }

      .modal-footer .btn-danger:hover {
        background: var(--dark-brown);
        box-shadow: 0 0 15px rgba(77, 45, 24, 0.9);
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
    <a class="nav-link" href="admin_cms.php"><i class="bi bi-body-text"></i>Content Management</a>
   
    <a class="nav-link text-danger logout" href="admin_logout.php" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-left"></i>Logout</a>
  </nav>
</div>

<!-- LOGOUTTTTT -->

<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body">
        Are you sure you want to log out?
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <!-- This goes to logout.php -->
        <a href="admin_logout.php" class="btn btn-danger">Yes, Logout</a>
      </div>
    </div>
  </div>
</div>
