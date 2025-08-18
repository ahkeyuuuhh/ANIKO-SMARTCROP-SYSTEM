<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aniko Footer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .footer-aniko {
            background-color: #2d5a3d;
            color: white;
            padding: 40px 0 20px 0;
        }
        
        .footer-aniko .logo-section {
            margin-bottom: 20px;
        }
        
        .footer-aniko .logo-section img {
            height: 40px;
            margin-bottom: 15px;
        }
        
        .footer-aniko .contact-info {
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .footer-aniko .social-icons a {
            color: white;
            font-size: 18px;
            margin-right: 15px;
            text-decoration: none;
            transition: opacity 0.3s ease;
        }
        
        .footer-aniko .social-icons a:hover {
            opacity: 0.7;
        }
        
        .footer-aniko .footer-links {
            margin-bottom: 30px;
        }
        
        .footer-aniko .footer-links h6 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: white;
        }
        
        .footer-aniko .footer-links ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-aniko .footer-links ul li {
            margin-bottom: 8px;
        }
        
        .footer-aniko .footer-links ul li a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            transition: opacity 0.3s ease;
        }
        
        .footer-aniko .footer-links ul li a:hover {
            opacity: 0.7;
        }
        
        .footer-aniko .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 20px;
            text-align: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }
        
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
            padding: 50px 0;
            text-align: center;
        }
    </style>
</head>
<body>
   

   
    <footer class="footer-aniko">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="logo-section">
                        <img src="IMG/Logo-hr.png" alt="Aniko Logo" class="img-fluid">
                        <div class="contact-info">
                            Call us at<br>
                            09 125 425 1234
                        </div>
                        <div class="social-icons">
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-links">
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">About</a></li>
                            <li><a href="#">Feature</a></li>
                            <li><a href="#">Testimonial</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-links">
                        <ul>
                            <li><a href="#">Download</a></li>
                            <li><a href="#">Why Aniko</a></li>
                            <li><a href="#">Team</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <div class="footer-links">
                        <ul>
                            <li><a href="#">Location</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Blog</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="footer-links">
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="copyright">
                        Copy Right 2025, Aniko
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
