<?php
session_start();
require 'CONFIG/config.php';
if (isset($_SESSION['email'])) {
  include 'INCLUDE/header-logged.php';
} else {
  include 'INCLUDE/header-unlogged.php';
}

$sql = "SELECT t.testimonial, t.created_at, a.name, a.picture 
        FROM testimonials t 
        JOIN accounts a ON t.user_id = a.id 
        WHERE t.status = 'approved'
        ORDER BY t.created_at DESC";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Testimonials</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">
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
      background:var(--gradient-primary);
      color: var(--foreground);
      font-family: 'Open Sans', sans-serif;
      line-height: 1.6;
    }

    .testimonials-masonry {
      column-count: 1;
      column-gap: 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    @media (min-width: 768px) {
      .testimonials-masonry {
        column-count: 2;
      }
    }

    @media (min-width: 1024px) {
      .testimonials-masonry {
        column-count: 3;
      }
    }

    .button {
      background-color: transparent;
      padding: 8px 16px;
      border: 2px solid var(--primary-green);
      width: 15%;
      color: var(--primary-green);
      border-radius: 20px;
      justify-content: center;
      align-items: center;
      margin: 0 auto 2rem auto;
      display: block;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease-in-out;
      position: relative;
      overflow: hidden;
      text-decoration: none !important;
      box-shadow: 0px 0px 20px 5px var(--accent-green);
      text-align: center;
    }

    .button:hover {
      transform: translateY(-5px) scale(1.05);
      background-color: var(--primary-green);
      border-color: var(--primary-green);
      color: var(--white);
    }

    .button::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      transform: translate(-50%, -50%);
      transition: width 0.4s ease, height 0.4s ease;
    }

    .button:hover::after {
      width: 200%;
      height: 500%;
    }

    h2 {
      color: var(--light-green) !important;
      text-shadow: 0px 0px 5px var(--accent-green) !important;
    }

    .header-subtext {
      text-align:center;
      width: 55%;
      justify-content: center;
      align-items: center;
      display: block;
      margin: -2rem auto 0;
      margin-bottom: 1.5rem;
      color: var(--light-green);
    }

    .testimonial-card {
      background-color: rgba(144, 238, 144, 0.32); 
      border: none;
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      break-inside: avoid;
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 32px rgba(74, 124, 43, 0.15);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      backdrop-filter: brightness(0.85) contrast(0.9) saturate(0.8) blur(6px);
      -webkit-backdrop-filter: brightness(0.85) contrast(0.9) saturate(0.8) blur(6px);
    }

    .testimonial-card::before {
      content: "";
      position: absolute;
      inset: 0;
      border-radius: 20px;
      filter: brightness(0.7) contrast(0.85) saturate(0.7);
      opacity: 0.8;
      z-index: -1;  
    }

    .testimonial-card:hover {
      transform: translateY(-8px) rotate(1deg);
      box-shadow: 0 20px 60px rgba(74, 124, 43, 0.2);
    }

    .testimonial-user {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1.5rem;
      position: relative;
      z-index: 2;
    }

    .testimonial-user img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      margin-right: 1rem;
      border: 4px solid var(--primary-green );
      box-shadow: 0 4px 16px rgba(74, 124, 43, 0.3);
      position: relative;
      z-index: 3;
      transition: transform 0.3s ease;
    }

    .testimonial-card:hover .testimonial-user img {
      transform: scale(1.1);
    }

    .testimonial-user-info h5 {
      margin: 0 0 0.25rem 0;
      color: var(--dark-green);
      font-family: 'Work Sans', sans-serif;
      font-weight: 600;
      font-size: 1.1rem;
    }

    .testimonial-user-info small {
      color: var(--secondary);
      font-weight: 500;
      font-size: 0.85rem;
    }

    .testimonial-text {
      font-size: 1rem;
      color: var(--foreground);
      position: relative;
      z-index: 2;
      font-style: italic;
      line-height: 1.7;
      margin: 0;
    }

    .testimonial-heading {
      color: var(--primary);
      font-family: 'Work Sans', sans-serif;
      font-weight: 700;
      font-size: 3rem;
      text-align: center;
      margin-bottom: 3rem;
      position: relative;
    }

    .testimonial-heading::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 200px;
      height: 60px;
      background: var(--accent);
      opacity: 0.1;
      border-radius: 50px 20px 50px 20px;
      z-index: -1;
    }

    .btn-success {
      background: transparent !important;
      border: 2px solid var(--primary-green) !important;
      color: var(--primary-green) !important;
      font-weight: 600 !important;
      font-family: 'Work Sans', sans-serif;
      padding: 10px 20px !important;
      border-radius: 50px !important;
      font-size: 1.1rem !important;
      transition: all 0.3s ease !important;
      position: relative !important;
      overflow: hidden !important;
    }

    .btn-success::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s;
    }

    .btn-success:hover::before {
      left: 100%;
    }

    .btn-success:hover {
      background-color: var(--primary-green);
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 12px 32px rgba(74, 124, 43, 0.4);
    }

    .testimonial-item {
      opacity: 0;
      animation: fadeInUp 0.6s ease forwards;
    }

    .testimonial-item:nth-child(1) { animation-delay: 0.1s; }
    .testimonial-item:nth-child(2) { animation-delay: 0.2s; }
    .testimonial-item:nth-child(3) { animation-delay: 0.3s; }
    .testimonial-item:nth-child(4) { animation-delay: 0.4s; }
    .testimonial-item:nth-child(5) { animation-delay: 0.5s; }
    .testimonial-item:nth-child(6) { animation-delay: 0.6s; }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .hidden-testimonial {
      display: none;
    }

    @media (max-width: 767px) {
      .testimonial-heading {
        font-size: 2.5rem;
      }
      
      .testimonial-card {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
      }
      
      .testimonial-card::after {
        font-size: 80px;
        top: -10px;
        right: 15px;
      }
    }
  </style>
</head>
<body>
  <div class="container my-5">
    <h2 class="testimonial-heading">Hear Directly From Our Users</h2>
    <p class="header-subtext">We don’t just create solutions—we build relationships that last. Discover how our work has made a difference through the voices of those who matter most.</p>
    <a href="testimonial-submit.php" class="button">Submit Testimonial</a>


    <div class="testimonials-masonry" id="testimonial-container">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php 
          $count = 0;
          while ($row = $result->fetch_assoc()): 
            $hiddenClass = ($count >= 9) ? "hidden-testimonial" : "";
        ?>
          <div class="testimonial-item <?php echo $hiddenClass; ?>">
            <div class="testimonial-card">
              <div class="testimonial-user">
                <img src="<?php echo htmlspecialchars($row['picture']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                <div class="testimonial-user-info">
                  <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                  <small><?php echo date("M d, Y", strtotime($row['created_at'])); ?></small>
                </div>
              </div>
              <p class="testimonial-text"><?php echo htmlspecialchars($row['testimonial']); ?></p>
            </div>
          </div>
        <?php 
          $count++;
          endwhile; 
        ?>
      <?php else: ?>
        <p class="text-center" style="color: var(--primary); font-size: 1.2rem;">No approved testimonials yet.</p>
      <?php endif; ?>
    </div>

  <?php if ($count > 9): ?>
  <div class="text-center mt-5">
    <button id="viewMoreBtn" class="btn btn-success">View More Stories</button>
    <button id="showLessBtn" class="btn btn-success" style="display: none;">Show Less Stories</button>
  </div>
<?php endif; ?>

  </div>

 <script>
  const viewMoreBtn = document.getElementById('viewMoreBtn');
  const showLessBtn = document.getElementById('showLessBtn');
  const hiddenItems = document.querySelectorAll('.hidden-testimonial');

  viewMoreBtn?.addEventListener('click', function () {
    hiddenItems.forEach((el, index) => {
      setTimeout(() => {
        el.style.display = 'block';
        el.style.animation = `fadeInUp 0.6s ease forwards`;
      }, index * 100);
    });
    viewMoreBtn.style.display = 'none';
    showLessBtn.style.display = 'inline-block';
  });

  showLessBtn?.addEventListener('click', function () {
    hiddenItems.forEach(el => {
      el.style.display = 'none';
    });
    showLessBtn.style.display = 'none';
    viewMoreBtn.style.display = 'inline-block';
    window.scrollTo({ top: 0, behavior: 'smooth' }); // optional: scroll back up
  });
</script>


  <?php include 'INCLUDE/footer.php';?>

</body>
</html>
