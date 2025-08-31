<?php 
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

 include 'INCLUDE/header-logged.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    }
    
    .contact-section {
        padding: 60px 0;
        position: relative;
        overflow: hidden;
        margin-top: -2rem;
        margin-bottom: 2rem;
    }
    
    .contact-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="%23ffffff" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    }
    
    .contact-container {
        position: relative;
        z-index: 2;
    }
    
    .contact-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
    }
    
    .contact-info-section {
        background: var(--gradient-secondary);
        color: var(--light-green);
        padding: 60px 40px;
        position: relative;
        z-index: 3;
        border-top-left-radius: 20px;
        border-top-right-radius: 250px !important;
        border-bottom-left-radius: 20px;
    }

    .contact-info-section h3 {
        color: var(--light-green);
        font-weight: bold;
        margin-bottom: 2rem;
    }
    
    .contact-form-section {
        background: var(--light-green);
        padding: 60px 40px 60px 80px;
        position: relative;
        margin-left: -40px;
        box-shadow: -20px 0 40px rgba(0, 0, 0, 0.1);
        border-top-left-radius: 200px;
        border-top-right-radius: 20px;
        border-bottom-right-radius: 20px;
        color: var(--primary-green);
    }
    
    .section-title {
        text-align: center;
        margin-bottom: 80px;
        position: relative;
    }
    
    .section-title h2 {
        font-size: 3rem;
        font-weight: 800;
        color: var(--c5);
        margin-bottom: 20px;
        position: relative;
    }
    
    .section-title h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--c3), var(--c6));
        border-radius: 2px;
    }
    
    .section-title p {
        font-size: 1.2rem;
        color: var(--c4);
        max-width: 600px;
        margin: 0 auto;
        font-weight: 500;
    }
    
    .contact-info h3 {
        font-size: 2rem;
        margin-bottom: 40px;
        font-weight: 700;
        color: var(--c2);
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 35px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        border-left: 4px solid var(--light-green);
        transition: all 0.3s ease;
    }
    
    .contact-item:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateX(10px);
    }
    
    .contact-item i {
        font-size: 24px;
        margin-right: 20px;
        width: 30px;
        color: var(--light-green);
        margin-top: 5px;
    }
    
    .contact-item-content {
        display: block;
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: var(--light-green);
        font-weight: 500 !important;
    }
    
    .form-floating {
        margin-bottom: 25px;
    }
    
    .form-control {
        border: 2px solid var(--pastel-green) !important;
        border-radius: 20px !important;
        padding: 20px 40px;
        background: var(--c7);
        transition: all 0.3s ease;
        font-size: 1rem;
        color: var(--primary-green) !important;
    }
    
    .form-control:hover {
        border: 2px solid var(--primary-green) !important;
    }

    .form-control:focus {
        border:2px solid var(--primary-brown) !important;
        box-shadow: 0 0 0 0.25rem rgba(76, 100, 68, 0.25);
    }
    
    .form-floating > label {
        color: var(--primary-green);
        font-weight: 500;
    }
    
    .form-select {
        border: 2px solid var(--pastel-green) !important;
        border-radius: 15px;
        padding: 15px;
        background: var(--c7);
        color: var(--primary-brown);
    }

    .form-select:hover {
        border: 2px solid var(--primary-green) !important;
    }
    
    .form-select:focus {
        border-color: var(--c6);
        box-shadow: 0 0 0 0.25rem rgba(76, 100, 68, 0.25);
    }
    
    .btn-submit {
        background: var(--gradient-secondary) !important;
        border: none !important;
        border-radius: 20px !important;
        padding: 10px 40px !important;
        font-weight: 700 !important;
        font-size: 1.1rem !important;
        color: var(--light-green) !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        overflow: hidden !important;
        margin-top: 2rem;
    }
    
    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-submit:hover::before {
        left: 100%;
    }
    
    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(76, 100, 68, 0.4);
        background: var(--gradient-primary) !important;
    }
    
    .social-links {
        margin-top: 40px;
        display: flex;
        gap: 15px;
    }
    
    .social-links a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        background: var(--light-green);
        border-radius: 50%;
        color: var(--primary-green);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-decoration: none;
    }
    
    .social-links a::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--c2);
        border-radius: 50%;
        transform: scale(0);
        transition: transform 0.3s ease;
    }
    
    .social-links a:hover::before {
        transform: scale(1);
    }
    
    .social-links a i {
        position: relative;
        z-index: 2;
        font-size: 18px;
    }
    
    .social-links a:hover {
        transform: translateY(-5px);
        color: var(--c5);
    }

    .form-check-input {
        border-color: var(--primary-green) !important;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-green) !important;
        border-color: var(--dark-green);
    }
    
    .form-check-label {
        color: var(--c4);
        font-weight: 500;
    }
    
    @media (max-width: 992px) {
        .contact-wrapper {
            grid-template-columns: 1fr;
            gap: 0;
        }
        
        .contact-info-section {
            clip-path: none;
            padding: 40px 30px;
        }
        
        .contact-form-section {
            margin-left: 0;
            padding: 40px 30px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .contact-section {
            padding: 60px 0;
        }
        
        .section-title {
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2rem;
        }
        
        .contact-info-section,
        .contact-form-section {
            padding: 30px 20px;
        }
    } 
    
    .ok-btn:hover {
        background-color: var(--primary-green) !important;
        border: 2px solid var(--dark-green) !important;
        color: var(--light-green) !important;
    }
</style>
</head>
<body>
    <section class="contact-section">
        <div class="container-fluid contact-container">
            <div class="contact-wrapper">
                <div class="contact-info-section">
                    <h3>Get In Touch</h3>
                    
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div class="contact-item-content">
                            Olongapo City, Zambales, Philippines
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div class="contact-item-content">
                            0912-123-1234
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div class="contact-item-content">
                            anikosmartcropsystem@gmail.com
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div class="contact-item-content">9:00 AM - 10:00 PM</div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="contact-form-section">
                   <form action="process_contact.php" method="POST">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                  <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required>
                                    <label for="firstName">First Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                 <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required>
                                    <label for="lastName">Last Name</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                         <div class="col-md-6">
                            <div class="form-floating">
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    placeholder="Email Address" 
                                    value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>" 
                                    <?php echo isset($_SESSION['email']) ? 'readonly' : ''; ?> 
                                    required
                                >
                                <label for="email">Email Address</label>
                            </div>
                        </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                   <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                                    <label for="phone">Phone Number</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                         <select class="form-select" id="subject" name="subject" required>
                            <option value="disable" disabled selected>Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="support">Technical Support</option>
                            <option value="sales">Sales Question</option>
                            <option value="partnership">Partnership Opportunity</option>
                            <option value="feedback">Feedback</option>
                            <option value="other">Other</option>
                        </select>
                        </div>
                        
                        <div class="form-floating mb-4">
                         <textarea class="form-control" id="message" name="message" placeholder="Your message" style="height: 120px" required></textarea>
                            <label for="message">Tell us how we can help you...</label>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                               <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter">
                                <label class="form-check-label" for="newsletter">
                                    I'd like to receive updates and news
                                </label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-submit w-100">
                            <i class="fas fa-paper-plane me-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:20px; border: 2px solid var(--dark-green);border-top: 0 !important;">
          <div class="modal-header bg-success text-white" style="background: var(--gradient-secondary);color: var(--light-green);border: 0; border-top: 2px solid var(--dark-green); border-top-right-radius: 20px; border-top-left-radius: 20px;">
            <h5 class="modal-title" id="successModalLabel"><i class="fas fa-check-circle me-2"></i>Message Sent</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center" style="background-color: var(--light-green); color: var(--primary-green); font-weight:500;padding: 30px;">
            Your message has been successfully sent. <br> We’ll get back to you soon!
          </div>
          <div class="modal-footer" style="padding: 0px !important;">
            <button type="button" class="btn btn-success ok-btn" data-bs-dismiss="modal" style="border-radius: 10px; background-color: var(--pastel-green); border: 2px solid var(--primary-green); color: var(--primary-green); font-weight: 500; padding:5px 20px !important;
            margin: 10px;">OK</button>
          </div>
        </div>
      </div>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
      });
    </script>
    <?php unset($_SESSION['success']); endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'INCLUDE/footer.php';?>
</body>
</html>
