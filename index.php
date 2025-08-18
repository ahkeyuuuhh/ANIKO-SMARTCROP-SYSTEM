<?php
session_start();

 
if (isset($_SESSION['email'])) {
    include 'INCLUDE/header-logged.php';
} else {
    include 'INCLUDE/header-unlogged.php';
}
  

require __DIR__ . '/../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId("914921820277-65g7cco12fl293e2o9u1v1kd1rdfcrmk.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/ANIKOWEB/ANIKO-SMARTCROP-SYSTEM/gClientSetup.php");
$client->addScope("email");
$client->addScope("profile");

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
      background: var(--c1) !important;
      margin: 0;
      font-family: system-ui, sans-serif;
    }

    /* HERO */
    .hero {
      padding: 40px 20px;
    
    }

    .hero h1 {
      font-size: clamp(1.8rem, 4vw, 2.8rem); 
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

    
    .home-img-container {
      margin-top: -80px; 
      text-align: center;
    }

    .home-img-container img {
      max-width: 100%;
      height: auto;
    }

  
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

   
    .custom-line {
      max-width: 90%; 
      height: 3px;
      background: var(--c5);
      margin: 20px auto;
      border: none;
      border-radius: 2px;
    }

   /* ANIKO IN NUMBERS */
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

     /* FEATURES SECTION */
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

   
    .farmer-section h3 {
      font-size: clamp(1.2rem, 2vw, 1.6rem);
    }

    .farmer-section p {
      font-size: 1rem;
    }

   
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
 /* TESTIMONIAL SECTION */
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

.testimonial-scroll {
  display: flex;
  flex-wrap: nowrap;
  overflow-x: auto;
  scroll-behavior: smooth;
  padding-bottom: 10px;
}


.testimonial-scroll::-webkit-scrollbar {
  display: none;
}
.testimonial-scroll {
  -ms-overflow-style: none;  
  scrollbar-width: none;    
}


.testimonial-card {
  flex: 0 0 calc(33.333% - 1rem);
  min-width: 280px;
  
}
/* WHY ANIKO SECTION */
 .why-aniko {
    background-color: #1D492C;
    padding: 80px 0;
  }
  .why-aniko h2,
  .why-aniko p {
    color: #fff;
  }
  .why-aniko .card {
    border-radius: 20px;
    overflow: hidden;
  }
  .why-aniko .card-body img {
    display: block;
    margin: 0 auto;
  }
/* TEAM SECTION */
   .team-section {
    margin-top: -50px;
  }
  .team-section .team-img {
    width: 100%;
    height: 300px;
    object-fit: cover;
  }
  .team-overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 800px;
    padding: 20px;
  }
  .team-overlay h2 {
    font-size: 2.5rem;
    margin-bottom: 15px;
  }
  .team-overlay p {
    font-size: 1.2rem;
    line-height: 1.6;
  }
  
 .team-member-img {
    width: 180px;
    height: 180px;
    object-fit: cover;
    border: 5px solid #fff;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.2);
  }
  .team-members h5 {
    margin-top: 10px;
    font-size: 1.2rem;
  }
  .team-members p {
    margin-bottom: 0;
  }

  html {
  scroll-behavior: smooth;
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

    <div class="home-img-container" >
      <img src="IMG/home-image.png" alt="Home Image" class="img-fluid">
    </div>

    <p class="subtext"  id="about">
      Aniko is a smart soil monitoring app that helps you track moisture, temperature, 
      sunlight, and humidity in real time. Designed for farmers and growers, it empowers 
      you to make better decisions for healthier crops and higher yields.
    </p>

    <hr class="custom-line">

    <h2 class="section-heading" >Aniko in numbers</h2>
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
  <section class="farmer-section" id="features">
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
    <div class="row align-items-center mb-4">
      <div class="col-lg-8">
        <h2 class="fw-bold text-dark"  id="download">What Our Farmers Say</h2>
        <p class="text-muted mb-0">Real experiences from real farmers who are growing smarter with Aniko.</p>
      </div>
      <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
        <?php
        if (isset($_SESSION['email'])) {
            $button_link = "testimonial-submit.php";
        } else {
            $button_link = htmlspecialchars($login_url);
        }
        ?>
        <a href="<?php echo $button_link; ?>" class="btn btn-primary">Submit Now!</a>
      </div>
    </div>

    <div class="testimonial-scroll d-flex gap-3" id="testimonialScroll">
      <?php
      include 'CONFIG/config.php';

      $sql = "SELECT t.testimonial, t.created_at, a.name, a.email, a.picture
              FROM testimonials t
              JOIN accounts a ON t.user_id = a.id
              WHERE t.status = 'approved'
              ORDER BY t.created_at DESC";
      $result = $con->query($sql);

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $profile_pic = !empty($row['picture'])
                  ? $row['picture']
                  : ("https://www.gravatar.com/avatar/" . md5(strtolower(trim($row['email']))) . "?d=mp&s=80");

              echo '<div class="testimonial-card card shadow-sm flex-shrink-0">';
              echo '  <div class="card-body">';
              echo '    <div class="d-flex align-items-center mb-3">';
              echo '      <img src="' . htmlspecialchars($profile_pic) . '" class="rounded-circle me-3" width="50" height="50" alt="Profile">';
              echo '      <div>';
              echo '        <h6 class="mb-0">' . htmlspecialchars($row['name']) . '</h6>';
              echo '        <small class="text-muted">' . htmlspecialchars($row['email']) . '</small>';
              echo '      </div>';
              echo '    </div>';
              echo '    <p class="card-text">' . htmlspecialchars($row['testimonial']) . '</p>';
              echo '  </div>';
              echo '  <div class="card-footer text-muted">';
              echo '    <small>Posted on ' . date("F j, Y", strtotime($row['created_at'])) . '</small>';
              echo '  </div>';
              echo '</div>';
          }
      } else {
          echo '<p class="text-muted">No approved testimonials yet.</p>';
      }
      ?>"
    </div>

      <div class="text-center mt-5">
        <img src="IMG/download-now.png" alt="Download Now" class="img-fluid" style="max-width: 1000px;">
      </div>

  </div>
</section>

<!-- WHY ANIKO Section -->
<section id="why-aniko" class="why-aniko">
  <div class="container">
    <div class="row align-items-center">
    
      <div class="col-lg-6 text-white mb-4 mb-lg-0">
        <h2 class="fw-bold mb-3">Why Aniko?</h2>
        <p class="lead">We help farmers and agribusinesses save the world by
          improving production efficiency, innovating cultivation
          techniques, and optimizing resource use through 
          market data analysis. Aniko is the right solution
          for more sustainable and advanced agriculture.</p>
      </div>

     
      <div class="col-lg-6 text-center">
        <img src="IMG/why-aniko1.png" alt="Why Aniko" class="img-fluid rounded">
      </div>
    </div>

  
    <div class="card mt-5 shadow-lg border-0">
      <div class="card-body bg-white text-center p-4">
        <div class="row">
         
          <div class="col-md-4 mb-3 mb-md-0">
            <img src="IMG/why-icon1.png" alt="Icon 1" class="mb-3" width="60">
         
            <p class="mb-0 text-muted">The only real-time solution for managing soil and plant health</p>
          </div>

          <div class="col-md-4 mb-3 mb-md-0 border-start border-end">
            <img src="IMG/why-icon1.png" alt="Icon 2" class="mb-3" width="60">
           
            <p class="mb-0 text-muted">Over 40% of crop loss are caused by extreme weather conditions</p>
          </div>

          <div class="col-md-4">
            <img src="IMG/why-icon1.png" alt="Icon 3" class="mb-3" width="60">
            <p class="mb-0 text-muted">Over 40% of crop loss stem from poor plant disease diagnosis.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- TEAM IMAGE Section -->
<section class="team-section position-relative"  id="team">
  <div class="container-fluid p-0">
    <img src="IMG/team-image.png" alt="Our Team" class="img-fluid team-img w-100">
    
   
    <div class="team-overlay text-center text-white">
      <h2 class="fw-bold">Meet the Team</h2>
      <p class="lead">We are five 3rd-year IT students who share a passion for technology 
      and innovation, each bringing unique skills and perspectives to create 
      impactful, real-world solutions together.</p>
    </div>
  </div>
</section>

<!-- TEAM MEMBERS Section -->
<section class="team-members py-5">
  <div class="container text-center">
    <div class="row justify-content-center mb-4">
    
      <div class="col-md-4 mb-4">
        <img src="IMG/team-tian.png" alt="Member 1" class="rounded-circle mb-3 team-member-img">
        <h5 class="fw-bold">Troy Genrick Angeles</h5>
        <p class="text-muted">3rd Year IT Student</p>
      </div>
    
      <div class="col-md-4 mb-4">
        <img src="IMG/team-tian.png" alt="Member 2" class="rounded-circle mb-3 team-member-img">
        <h5 class="fw-bold">Chlouisse Amarue Ebuenga</h5>
        <p class="text-muted">3rd Year IT Student</p>
      </div>
    
      <div class="col-md-4 mb-4">
        <img src="IMG/team-tian.png" alt="Member 3" class="rounded-circle mb-3 team-member-img">
        <h5 class="fw-bold">Yestin Ronniel Guarin</h5>
        <p class="text-muted">3rd Year IT Student</p>
      </div>
    </div>

    <div class="row justify-content-center">
     
      <div class="col-md-4 mb-4">
        <img src="IMG/team-tian.png" alt="Member 4" class="rounded-circle mb-3 team-member-img">
        <h5 class="fw-bold">Christian C. Roldan</h5>
        <p class="text-muted">3rd Year IT Student</p>
      </div>
    
      <div class="col-md-4 mb-4">
        <img src="IMG/team-aki.png" alt="Member 5" class="rounded-circle mb-3 team-member-img">
        <h5 class="fw-bold">Aki Cristel E. Zita</h5>
        <p class="text-muted">3rd Year IT Student</p>
      </div>
    </div>
  </div>
</section>



<?php include 'INCLUDE/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script>
document.addEventListener("DOMContentLoaded", function () {
  const scrollContainer = document.getElementById("testimonialScroll");
  let scrollAmount = 1; 
  let direction = 1; 

  function autoScroll() {
    if (!scrollContainer) return;

    scrollContainer.scrollLeft += scrollAmount * direction;

 
    if (scrollContainer.scrollLeft + scrollContainer.clientWidth >= scrollContainer.scrollWidth) {
      direction = -1;
    } else if (scrollContainer.scrollLeft <= 0) {
      direction = 1;
    }
  }


  setInterval(autoScroll, 20);
});
</script>
</body>
</html>
