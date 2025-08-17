<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

include 'INCLUDE/header.php';

// Initialize Google Client
$client = new Google_Client();
$client->setClientId("914921820277-65g7cco12fl293e2o9u1v1kd1rdfcrmk.apps.googleusercontent.com"); 
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/ANIKOWEB/ANIKO-SMARTCROP-SYSTEM/gClientSetup.php"); 
$client->addScope("email");
$client->addScope("profile");

// Generate login URL
$login_url = $client->createAuthUrl();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aniko</title>
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
      background: var(--c1);
      margin: 0;
      font-family: system-ui, sans-serif;
    }

    /* HERO */
    .hero {
      padding: 40px 20px;
      background: var(--c1); 
    }

    .hero h1 {
      font-size: clamp(1.8rem, 4vw, 2.8rem); /* responsive font size */
      font-weight: 700;
      color: var(--c4); 
    }

    .hero p {
      font-size: clamp(1rem, 2vw, 1.2rem);
      margin-top: 15px;
      color: var(--c8); 
    }

    .hero .hero-img {
      max-width: 250px;
      height: auto;
    }

    /* HOME IMAGE */
    .home-img-container {
      margin-top: -80px; 
      text-align: center;
    }

    .home-img-container img {
      max-width: 100%;
      height: auto;
    }

    /* SUBTEXT */
    .subtext {
      margin-top: 20px;
      text-align: center;
      font-size: 1rem;
      color: var(--c8);
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.6;
      padding: 0 15px;
    }

    /* LINE */
    .custom-line {
      max-width: 90%; /* make line responsive */
      height: 3px;
      background: var(--c5);
      margin: 20px auto;
      border: none;
      border-radius: 2px;
    }

    /* SECTION HEADING */
    .section-heading {
      text-align: center;
      font-size: clamp(1.5rem, 3vw, 2rem);
      font-weight: 700;
      color: var(--c4);
      margin-top: 40px;
    }

    .section-subtext {
      text-align: center;
      font-size: 1rem;
      color: var(--c8);
      max-width: 900px;
      margin: 15px auto 40px;
      line-height: 1.6;
      padding: 0 15px;
    }

    /* STATS */
    .stats-section {
      margin-top: 40px;
    }

    .stat-box {
      text-align: center;
      padding: 20px;
    }

    .stat-box img {
      width: 70px;
      height: 70px;
      object-fit: contain;
      margin-bottom: 15px;
    }

    .stat-box p {
      font-size: 0.95rem;
      color: var(--c8);
      margin: 0 auto;
      line-height: 1.4;
      max-width: 250px;
    }

    /* FARMER SECTION */
    .farmer-section {
      background: var(--c9);
      padding: 60px 20px;
      text-align: center;
    }

    .farmer-section h2 {
      color: var(--c7);
      font-size: clamp(1.6rem, 3vw, 2rem);
      font-weight: 700;
      margin: 0;
    }

    /* FEATURES */
    .farmer-section h3 {
      font-size: clamp(1.2rem, 2vw, 1.6rem);
    }

    .farmer-section p {
      font-size: 1rem;
    }

    /* BENEFITS */
    .benefit-card {
      background: var(--c7);
      border-radius: 20px;
      padding: 25px 20px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
      height: 100%;
      transition: transform 0.2s;
    }

    .benefit-card:hover {
      transform: translateY(-5px);
    }

    .benefit-card h5 {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 15px;
      color: var(--c9);
    }

    .benefit-card img {
      width: 50px;
      height: 50px;
      margin-bottom: 15px;
    }

    .benefit-card p {
      font-size: 0.95rem;
      color: var(--c9);
      margin: 0;
    }

    .testimonial-section {
  background: var(--c1); 
}

.testimonial-section h2 {
  font-size: 2rem;
  color: var(--c4);
}

.testimonial-section p {
  font-size: 1.1rem;
}

  </style>
</head>
<body>

  <!-- HOME SECTION -->
  <div class="container">
    <section class="hero">
      <div class="row align-items-center">
        <div class="col-lg-7 text-center text-lg-start">
          <h1>Free app for soil health monitoring</h1>
          <p>The all-in-one app that gives you real-time soil insights for healthier crops and bigger harvests. Download now and grow smarter!</p>
        </div>
        <div class="col-lg-5 text-center mt-4 mt-lg-0">
          <img src="IMG/google-play.png" alt="Download on Google Play" class="hero-img">
        </div>
      </div>
    </section>

    <div class="home-img-container">
      <img src="IMG/home-image.png" alt="Home Image" class="img-fluid">
    </div>

    <p class="subtext">
      Aniko is a smart soil monitoring app that helps you track moisture, temperature, 
      sunlight, and humidity in real time. Designed for farmers and growers, it empowers 
      you to make better decisions for healthier crops and higher yields.
    </p>

    <hr class="custom-line">

    <h2 class="section-heading">Aniko in numbers</h2>
    <p class="section-subtext">
      Discover how Aniko is transforming agriculture from real-time insights to improved crop yields — the numbers speak for themselves.
    </p>

    <div class="row stats-section">
      <div class="col-md-4 col-12 mb-4">
        <div class="stat-box">
          <img src="IMG/home-icon1.png" alt="Icon 1">
          <p>Continuous Soil Health Monitoring</p>
        </div>
      </div>
      <div class="col-md-4 col-12 mb-4">
        <div class="stat-box">
          <img src="IMG/home-icon2.png" alt="Icon 2">
          <p>Find the right treatment for more than 780 plant diseases</p>
        </div>
      </div>
      <div class="col-md-4 col-12 mb-4">
        <div class="stat-box">
          <img src="IMG/home-icon3.png" alt="Icon 3">
          <p>Detects over 5 climate anomalies.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- FEATURE SECTION -->
  <section class="farmer-section">
    <h2>A solution designed for farmers</h2>

    <div class="container mt-5">
      <div class="row align-items-center">
        <div class="col-lg-6 text-start">
          <h3 class="text-white fw-bold">Aniko</h3>
          <p class="text-light mb-4">Features</p>

          <div class="row g-4">
            <div class="col-6 d-flex align-items-center">
              <img src="IMG/feature-icon1.png" alt="Feature 1" class="me-3" style="width:45px; height:45px;">
              <p class="text-white mb-0">Climate Pattern Analysis</p>
            </div>
            <div class="col-6 d-flex align-items-center">
              <img src="IMG/feature-icon2.png" alt="Feature 2" class="me-3" style="width:45px; height:45px;">
              <p class="text-white mb-0">AI-Powered Plant Diagnosis</p>
            </div>
            <div class="col-6 d-flex align-items-center">
              <img src="IMG/feature-icon3.png" alt="Feature 3" class="me-3" style="width:45px; height:45px;">
              <p class="text-white mb-0">Soil Health Monitoring</p>
            </div>
            <div class="col-6 d-flex align-items-center">
              <img src="IMG/feature-icon4.png" alt="Feature 4" class="me-3" style="width:45px; height:45px;">
              <p class="text-white mb-0">AI-Powered Soil Health Check</p>
            </div>
          </div>
        </div>

        <div class="col-lg-6 text-center mt-4 mt-lg-0">
          <img src="IMG/feature-phone.png" alt="Aniko App" class="img-fluid" style="max-width:380px; border-radius:20px;">
        </div>
      </div>
    </div>

    <hr class="custom-line">

    <!-- BENEFITS -->
    <div class="container mt-5">
      <div class="row mb-4">
        <div class="col text-start">
          <h3 class="text-white fw-bold">Aniko</h3>
          <p class="text-light">Benefits</p>
        </div>
      </div>
      <div class="row g-4">
        <div class="col-md-4 col-12">
          <div class="benefit-card">
            <h5>Monitors</h5>
            <img src="IMG/benefits-icon1.png" alt="Benefit 1">
            <p>Monitor the field status 24/7</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div class="benefit-card">
            <h5>Save Resources</h5>
            <img src="IMG/benefits-icon2.png" alt="Benefit 2">
            <p>Predict Climate Anomalies</p>
          </div>
        </div>
        <div class="col-md-4 col-12">
          <div class="benefit-card">
            <h5>Stay Ahead</h5>
            <img src="IMG/benefits-icon3.png" alt="Benefit 3">
            <p>AI-Powered Application Features</p>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- TESTIMONIAL SECTION -->
  <section class="testimonial-section py-5">
    <div class="container">
      <div class="row align-items-center">
        <!-- Header & Subheader -->
        <div class="col-lg-8">
          <h2 class="fw-bold text-dark">What Our Farmers Say</h2>
          <p class="text-muted mb-0">
            Real experiences from real farmers who are growing smarter with Aniko.
          </p>
        </div>

        <!-- Button on Right -->
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <a href="<?php echo $client->createAuthUrl(); ?>">login with google </a>
        </div>

      </div>
    </div>
  </section>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
