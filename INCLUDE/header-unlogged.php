<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Floating Header</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
    }

    .floating-header {
      background: var(--c5);
      color: var(--c7);
      padding: 10px 20px;
      width: calc(100% - 40px);
      margin: 20px auto;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      position: sticky;
      top: 10px;
      z-index: 1000;
    }

    .floating-header .nav-link {
      color: var(--c7) !important;
      font-weight: 500;
      transition: 0.3s;
    }

    .floating-header .nav-link:hover {
      color: var(--c2) !important;
    }

    .navbar-toggler {
      border-color: var(--c7);
    }
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='white' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    .navbar-nav {
      margin: 0 auto; 
      text-align: center;
    }
  </style>
</head>
<body>

  <header class="floating-header">
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">
          <img src="IMG/logo-hr.png" alt="Aniko Logo" height="30" class="d-inline-block align-text-top">
        </a>    
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav gap-3">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
            <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
            <li class="nav-item"><a class="nav-link" href="#testimonial-display.php">Testimonial</a></li>
            <li class="nav-item"><a class="nav-link" href="#download">Download</a></li>
            <li class="nav-item"><a class="nav-link" href="#why-aniko">Why Aniko</a></li>
            <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
            <li class="nav-item"><a class="nav-link" href="compliance.php">Compliance</a></li>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
