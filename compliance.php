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
   
    
    <style>
      
        :root {
            --c1: #cfc4b2ff;
            --c2: #BDE08A;
            --c3: #8A6440;
            --c4: #4D2D18;
            --c5: #112822;
            --c6: #4C6444;
            --c7: #FFFFFF;
            --c8: #000000;
            --c9: #1D492C;
        }
        
       
        .contact-section {
            padding: 100px 0;
          
            position: relative;
            overflow: hidden;
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
            background: var(--c5);
            color: var(--c7);
            padding: 60px 40px;
            position: relative;
            clip-path: polygon(0 0, 85% 0, 100% 100%, 0 100%);
            z-index: 3;
        }
        
        .contact-form-section {
            background: var(--c7);
            padding: 60px 40px 60px 80px;
            position: relative;
            margin-left: -40px;
            box-shadow: -20px 0 40px rgba(0, 0, 0, 0.1);
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
            border-left: 4px solid var(--c2);
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
            color: var(--c2);
            margin-top: 5px;
        }
        
        .contact-item-content strong {
            display: block;
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: var(--c2);
        }
        
        .form-floating {
            margin-bottom: 25px;
        }
        
        .form-control {
            border: 2px solid var(--c1);
            border-radius: 15px;
            padding: 20px 15px 10px 15px;
            background: var(--c7);
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--c6);
            box-shadow: 0 0 0 0.25rem rgba(76, 100, 68, 0.25);
            background: var(--c7);
        }
        
        .form-floating > label {
            color: var(--c4);
            font-weight: 500;
        }
        
        .form-select {
            border: 2px solid var(--c1);
            border-radius: 15px;
            padding: 15px;
            background: var(--c7);
            color: var(--c4);
        }
        
        .form-select:focus {
            border-color: var(--c6);
            box-shadow: 0 0 0 0.25rem rgba(76, 100, 68, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, var(--c6) 0%, var(--c9) 100%);
            border: none;
            border-radius: 15px;
            padding: 18px 40px;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--c7);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
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
            background: var(--c6);
            border-radius: 50%;
            color: var(--c7);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
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
        
        .form-check-input:checked {
            background-color: var(--c6);
            border-color: var(--c6);
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
                            <strong>Visit Us</strong>
                            123 Creative Street<br>
                            Innovation District<br>
                            City, State 12345
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div class="contact-item-content">
                            <strong>Call Us</strong>
                            +1 (555) 123-4567<br>
                            +1 (555) 987-6543
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div class="contact-item-content">
                            <strong>Email Us</strong>
                            hello@company.com<br>
                            support@company.com
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <i class="fas fa-clock"></i>
                        <div class="contact-item-content">
                            <strong>Office Hours</strong>
                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                            Saturday: 10:00 AM - 4:00 PM<br>
                            Sunday: Closed
                        </div>
                    </div>
                    
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
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
                                    I'd like to receive updates and newsletters
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

   
</body>
</html>