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
      color: var(--c7);
    }
    .testimonial-card {
      background: var(--c5);
      border: 1px solid var(--c2);
      border-radius: 1rem;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      transition: transform 0.2s;
    }
    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 25px rgba(0, 255, 255, 0.4);
    }
    .testimonial-user {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
    }
    .testimonial-user img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      margin-right: 1rem;
      border: 2px solid var(--c2);
    }
    .testimonial-user h5 {
      margin: 0;
      color: var(--c2);
    }
    .testimonial-text {
      font-size: 1.1rem;
    }
    .hidden-testimonial {
      display: none;
    }
  </style>
</head>
<body>


  <div class="container my-5">
    <h2 class="text-center mb-4 text-cyan">What People Say</h2>
    <div class="row" id="testimonial-container">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php 
          $count = 0;
          while ($row = $result->fetch_assoc()): 
            $hiddenClass = ($count >= 9) ? "hidden-testimonial" : "";
        ?>
          <div class="col-md-6 col-lg-4 mb-4 testimonial-item <?php echo $hiddenClass; ?>">
            <div class="testimonial-card">
              <div class="testimonial-user">
                <img src="<?php echo htmlspecialchars($row['picture']); ?>" alt="User Picture">
                <div>
                  <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                  <small><?php echo date("M d, Y", strtotime($row['created_at'])); ?></small>
                </div>
              </div>
              <p class="testimonial-text">"<?php echo htmlspecialchars($row['testimonial']); ?>"</p>
            </div>
          </div>
        <?php 
          $count++;
          endwhile; 
        ?>
      <?php else: ?>
        <p class="text-center">No approved testimonials yet.</p>
      <?php endif; ?>
    </div>

   
    <?php if ($count > 9): ?>
      <div class="text-center mt-4">
        <button id="viewMoreBtn" class="btn btn-success px-4 py-2">View More</button>
      </div>
    <?php endif; ?>
  </div>

  
  <script>
    document.getElementById('viewMoreBtn')?.addEventListener('click', function () {
      document.querySelectorAll('.hidden-testimonial').forEach(el => {
        el.style.display = 'block';
      });
      this.style.display = 'none'; 
    });
  </script>
</body>
</html>
