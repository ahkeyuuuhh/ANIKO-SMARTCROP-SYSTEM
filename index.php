<?php
session_start();

require_once 'CONFIG/config.php'; 
require __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setClientId("67607885572-0unromtvovfl5bb73dmv8mb5shrop87n.apps.googleusercontent.com");
$client->setClientSecret("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l");
$client->setRedirectUri("http://localhost/ANIKO-SMARTCROP-SYSTEM/gClientSetup.php");
$client->addScope("email");
$client->addScope("profile");
$login_url = $client->createAuthUrl();

if (isset($_SESSION['email'])) {
  include 'INCLUDE/header-logged.php';
} else {
  include 'INCLUDE/header-unlogged.php';
}


$imagePath = '';
$sql = "SELECT image_path FROM home_images ORDER BY uploaded_at DESC LIMIT 1";
$result = $con->query($sql);
if ($result && $row = $result->fetch_assoc()) {
  $imagePath = $row['image_path'];
}

$benefitsImage = '';
$sql2 = "SELECT image_path FROM benefits_images ORDER BY uploaded_at DESC LIMIT 1";
$result2 = $con->query($sql2);
if ($result2 && $row2 = $result2->fetch_assoc()) {
  $benefitsImage = $row2['image_path'];
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aniko - Smart Soil Monitoring</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

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

    * {
      box-sizing: border-box;
    }

    body {
      background: var(--bg-color) !important;
      margin: 0;
      font-family: 'Manrope', system-ui, sans-serif;
      line-height: 1.6;
      color: var(--dark-gray);
      overflow-x: hidden;
    }

    .hero {
      background-image: url('IMG/hero-bg.jpg');
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      padding: 100px 20px 80px;
      position: relative;
      overflow: hidden;
      margin-top: -10rem;
      z-index: 1;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: inherit;      
      background-repeat: no-repeat;
      background-size: cover;
      background-position: center;
      filter: blur(6px) brightness(60%); 
      transform: scale(1.05);         
      z-index: -1;
    }


    .hero::after {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(132, 204, 22, 0.1) 0%, transparent 70%);
      animation: rotate 30s linear infinite;
    }

    .hero .container {
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      font-family: 'Inter', sans-serif;
      font-size: clamp(2.8rem, 6vw, 4.5rem);
      font-weight: 800;
      color: var(--light-green) !important;
      margin-bottom: 32px;
      text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      line-height: 1.1;
      letter-spacing: -0.02em;
      margin-top: 5rem;
    }

    .hero p {
      font-size: clamp(1.2rem, 3vw, 1.5rem);
      color: rgba(255, 255, 255, 0.95);
      margin-bottom: 40px;
      max-width: 650px;
      font-weight: 400;
      line-height: 1.7;
    }

    .hero-cta {
      display: inline-flex;
      align-items: center;
      gap: 16px;
      background: var(--gradient-accent);
      color: var(--accent-green);
      padding: 20px 40px;
      border-radius: 60px;
      text-decoration: none;
      font-weight: 700;
      font-size: 1.2rem;
      box-shadow: 0 8px 25px rgba(132, 204, 22, 0.4);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border: 2px solid rgba(255, 255, 255, 0.2);
      position: relative;
      overflow: hidden;
      box-shadow: 0px 0px 20px 5px var(--accent-green);
    }

    .hero-cta::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.6s;
    }

    .hero-cta:hover::before {
      left: 100%;
    }

    .hero-cta:hover {
      transform: translateY(-4px) scale(1.02);
      box-shadow: 0 12px 35px rgba(132, 204, 22, 0.5);
      color: var(--light-green);
    }

    .hero .hero-img {
      max-width: 320px;
      height: auto;
      filter: drop-shadow(0 15px 35px rgba(0, 0, 0, 0.3));
      transition: all 0.4s ease;
      animation: float 6s ease-in-out infinite;
    }

    .hero .hero-img:hover {
      transform: scale(1.08) rotate(2deg);
    }

    .home-img-container {
      text-align: center;
      margin: -60px 0 80px;
      position: relative;
      z-index: 3;
    }

    .home-img-container img {
      width: min(90%, 900px);
      height: auto;
      border-radius: 32px;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
      border: 6px solid var(--white);
      transition: all 0.4s ease;
    }

    .home-img-container img:hover {
      transform: translateY(-8px);
      box-shadow: 0 35px 70px rgba(0, 0, 0, 0.2);
    }

    .subtext {
      margin: 80px auto;
      text-align: center;
      font-size: 1.3rem;
      color: var(--dark-gray);
      max-width: 950px;
      line-height: 1.8;
      padding: 0 20px;
      font-weight: 400;
      position: relative;
    }

    .subtext::before {
      content: '';
      position: absolute;
      top: -20px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 4px;
      background: var(--gradient-accent);
      border-radius: 2px;
    }

    .custom-line {
      max-width: 150px;
      height: 6px;
      background: var(--gradient-primary);
      margin: 60px auto;
      border: none;
      border-radius: 3px;
      position: relative;
    }

    .custom-line::before {
      content: '';
      position: absolute;
      top: -2px;
      left: -2px;
      right: -2px;
      bottom: -2px;
      background: var(--gradient-glow);
      border-radius: 5px;
      z-index: -1;
    }

    .section-heading {
      text-align: center;
      font-family: 'Inter', sans-serif;
      font-size: clamp(2.5rem, 5vw, 3.5rem);
      font-weight: 800;
      color: var(--dark-brown);
      margin: 80px 0 24px;
      position: relative;
      letter-spacing: -0.02em;
    }

    .section-heading::after {
      content: '';
      position: absolute;
      bottom: -12px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--gradient-accent);
      border-radius: 2px;
    }

    .section-subtext {
      text-align: center;
      font-size: 1.2rem;
      color: #6b7280;
      max-width: 850px;
      margin: 0 auto 80px;
      line-height: 1.8;
      padding: 0 20px;
      font-weight: 400;
    }

    .stats-section {
      margin: 80px 0;
    }

    .stat-box {
      text-align: center;
      padding: 50px 30px;
      background: var(--light-green);
      border-radius: 28px;
      box-shadow: var(--shadow-soft);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      height: 100%;
      border: 2px solid rgba(21, 128, 61, 0.08);
      position: relative;
      overflow: hidden;
    }

    .stat-box::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: var(--gradient-accent);
      transform: scaleX(0);
      transition: transform 0.4s ease;
    }

    .stat-box:hover::before {
      transform: scaleX(1);
    }

    .stat-box:hover {
      transform: translateY(-12px) scale(1.02);
      box-shadow: 0 20px 40px rgba(21, 128, 61, 0.15);
      border-color: var(--accent-green);
    }

    .stat-box img {
      width: 90px;
      height: 90px;
      object-fit: contain;
      margin-bottom: 28px;
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
      transition: all 0.3s ease;
    }

    .stat-box:hover img {
      transform: scale(1.1) rotate(5deg);
    }

    .stat-box p {
      font-size: 1.15rem;
      color: var(--dark-gray);
      margin: 0 auto;
      line-height: 1.7;
      max-width: 300px;
      font-weight: 500;
    }

    .farmer-section {
      background-image: url('IMG/grad.jpg');
      background-size: cover;
      background-position: center;
      padding: 120px 20px;
      text-align: center;
      position: relative;
      overflow: hidden;
      margin-top: -16rem;
      z-index: -1;
    }

    .farmer-section .container {
      position: relative;
      z-index: 2;
    }

    .farmer-section h1 {
      font-family: 'Inter', sans-serif;
      color: var(--white);
      font-weight: 800;
      font-size: clamp(2.5rem, 5vw, 4rem);
      margin-bottom: 80px;
      text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      letter-spacing: -0.02em;
      margin-top: 8rem;
    }

    .solutionBenefits-con {
      text-align: left;
      max-width: 1300px;
      margin: 0 auto;
    }

    .solutions-row {
      align-items: center;
      justify-content: center;
      margin: 0 auto !important;
      display: flex;
    }

    .solution-text-side h3 {
      font-family: 'Inter', sans-serif;
      font-size: 3rem;
      font-weight: 800;
      color: var(--white);
      margin-bottom: 12px;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .solution-text-side > p {
      font-size: 1.4rem;
      color: rgba(255, 255, 255, 0.85);
      margin-bottom: 50px;
      font-weight: 400;
    }

    .solution-text-side .col-6 {
      margin-bottom: 32px;
    }

    .solution-text-side .col-6 p {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--white);
      text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    .sol-icon {
      padding: 5px;
      background: var(--white);
      border-radius: 50px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      box-shadow: 0px 0px 20px 2px var(--accent-green);
    }

    .sol-icon::before {
      content: '';
      position: absolute;
      inset: -2px;
      background: var(--gradient-accent);
      border-radius: 22px;
      z-index: -1;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .sol-icon:hover::before {
      opacity: 1;
    }

    .sol-icon:hover {
      transform: scale(1.15) rotate(5deg);
      box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
    }

    .solution-img-side img {
      transition: all 0.4s ease;
    }

    .solution-img-side img:hover {
      transform: scale(1.05) rotate(-2deg);
    }

    /* Enhanced benefit cards with sophisticated hover effects */
    .benefits-row {
      justify-content: center;
      align-items: center;
      margin: 0 auto !important;  
    }
    
    .benefit-card {
      background: var(--light-green);
      border-radius: 32px;
      padding: 50px 32px;
      text-align: center;
      box-shadow: 0px 0px 20px 2px var(--accent-green);
      height: 100%;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      border: 2px solid transparent;
      position: relative;
      overflow: hidden;
    }

    .benefit-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: var(--gradient-accent);
      transform: scaleX(0);
      transition: transform 0.4s ease;
    }

    .benefit-card::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      background: radial-gradient(circle, rgba(132, 204, 22, 0.1) 0%, transparent 70%);
      transform: translate(-50%, -50%);
      transition: all 0.4s ease;
      border-radius: 50%;
    }

    .benefit-card:hover::before {
      transform: scaleX(1);
    }

    .benefit-card:hover::after {
      width: 300px;
      height: 300px;
    }

    .benefit-card:hover {
      transform: translateY(-12px) scale(1.02);
      box-shadow: 0 25px 50px rgba(21, 128, 61, 0.15);
      border-color: var(--accent-green);
    }

    .benefit-card h5 {
      font-family: 'Inter', sans-serif;
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-green);
      position: relative;
      z-index: 2;
    }

    .benefit-card img {
      width: 72px;
      height: 72px;
      margin-bottom: 24px;
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
      transition: all 0.3s ease;
      position: relative;
      z-index: 2;
    }

    .benefit-card:hover img {
      transform: scale(1.15) rotate(10deg);
    }

    .benefit-card p {
      font-size: 1.05rem;
      color: var(--dark-gray);
      margin: 0;
      line-height: 1.7;
      font-weight: 400;
      position: relative;
      z-index: 2;
    }

    .testimonial-section {
      background: var(--bg-color);
      padding: 120px 0;
      position: relative;
    }

    .testimonial-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="testimonial-pattern" width="50" height="50" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="0.8" fill="rgba(21, 128, 61, 0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23testimonial-pattern)"/></svg>') repeat;
    }

    .testimonial-section .container {
      position: relative;
      z-index: 2;
    }

    .testimonial-section h2 {
      font-family: 'Inter', sans-serif;
      font-size: clamp(2.5rem, 5vw, 3.5rem);
      font-weight: 800;
      color: var(--dark-brown) !important;
      margin-bottom: 20px;
      letter-spacing: -0.02em;
    }

    .testimonial-section > .container > .row > .col-lg-8 > p {
      font-size: 1.3rem;
      color: #6b7280;
      margin-bottom: 0;
      line-height: 1.6;
    }

    .submit-btn {
      background: var(--gradient-primary);
      color: var(--white) !important;
      font-weight: 500 !important;
      border-radius: 20px !important;
      padding: 10px 20px !important;
      border: none !important;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      display: inline-block;
      font-size: 1.1rem;
      position: relative;
      overflow: hidden;
    }

    .submit-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.6s;
    }

    .submit-btn:hover::before {
      left: 100%;
    }

    .submit-btn:hover {
      transform: translateY(-3px) scale(1.02);
      box-shadow: 0 12px 35px rgba(21, 128, 61, 0.4);
      color: var(--white);
    }

    .testimonial-scroll {
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
      scroll-behavior: smooth;
      padding: 30px 0 50px;
      gap: 32px;
    }

    .testimonial-scroll::-webkit-scrollbar {
      height: 0px;
    }

    .testimonial-scroll::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 5px;
    }

    .testimonial-scroll::-webkit-scrollbar-thumb {
      background: var(--gradient-accent);
      border-radius: 5px;
    }

    .testimonial-card {
      flex: 0 0 calc(33.333% - 22px);
      min-width: 350px;
      border-radius: 28px !important;
      border: 2px solid #e5e7eb;
      background: var(--light-green) !important;
      box-shadow: var(--shadow-soft);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
      position: relative;
    }

    .testimonial-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--gradient-accent);
      transform: scaleX(0);
      transition: transform 0.4s ease;
    }

    .testimonial-card:hover::before {
      transform: scaleX(1);
    }

    .testimonial-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 20px 40px rgba(21, 128, 61, 0.12);
      border-color: var(--accent-green);
    }

    .testimonial-card .card-body {
      padding: 40px 32px 32px;
    }

    .testimonial-name {
      font-weight: 700;
      color: var(--primary-green);
      font-size: 1.2rem;
    }

    .testimonial-email {
      color: #6b7280;
      font-size: 0.95rem;
    }

    .testimonial-text {
      color: var(--dark-gray);
      font-weight: 400;
      line-height: 1.7;
      margin-top: 20px;
      font-size: 1rem;
    }

    .card-footer {
      background: var(--pastel-green) !important;
      border-top: 1px solid #e5e7eb;
      color: var(--primary-green);
      font-weight: 600;
      padding: 20px 32px;
    }

    .viewTestimonial-btn {
      background: var(--primary-brown);
      border: none;
      border-radius: 60px;
      padding: 10px 40px;
      margin: 0 auto;
      display: block;
      width: fit-content;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .viewTestimonial-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.6s;
    }

    .viewTestimonial-btn:hover::before {
      left: 100%;
    }

    .viewTestimonial-btn:hover {
      transform: translateY(-3px) scale(1.02);
      box-shadow: 0 12px 35px rgba(132, 204, 22, 0.4);
    }

    .viewTestimonial-btn a {
      text-decoration: none;
      color: var(--white);
      font-weight: 500;
      font-size: 1.2rem;
    }

    .why-aniko {
      background: var(--primary-green);
      padding: 5rem 0;
      position: relative;
      overflow: hidden;
    }
    
    .why-aniko .container {
      position: relative;
      z-index: 2;
    }

    .why-aniko h2 {
      font-family: 'Inter', sans-serif;
      color: var(--accent-green);
      font-size: clamp(2.5rem, 5vw, 3.5rem);
      font-weight: 800;
      margin-bottom: 32px;
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      letter-spacing: -0.02em;
    }

    .why-aniko p {
      color: rgba(255, 255, 255, 0.92);
      font-size: 1.3rem;
      line-height: 1.8;
      font-weight: 400;
    }

    .why-aniko img {
      transition: all 0.4s ease;
    }

    .why-aniko img:hover {
      transform: scale(1.05) rotate(-1deg);
    }

    .why-aniko-card {
      box-shadow: 0px 0px 30px 5px var(--accent-green);
      border-radius: 32px !important;
      border: 3px solid rgba(132, 204, 22, 0.3);
      overflow: hidden;
      margin-top: 80px;
      position: relative;
      padding: 1rem;
      background-color: var(--dark-green) !important;
    }

    .why-aniko-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 6px;
      background: var(--gradient-accent);
    }

    .why-aniko-card .card-body {
      padding: 80px 50px;
      background-color: var(--dark-green)!important;
      color: white !important;
    }

    .why-aniko-card .col-md-4 {
      padding: 0 40px;
      position: relative;
    }

    .why-aniko-card .col-md-4::after {
      content: '';
      position: absolute;
      top: 20px;
      bottom: 20px;
      right: 0;
      width: 2px;
      background: linear-gradient(to bottom, transparent, var(--accent-green), transparent);
    }

    .why-aniko-card .col-md-4:last-child::after {
      display: none;
    }

    .why-aniko-card img {
      width: 80px;
      height: 80px;
      margin-bottom: 32px;
      filter: drop-shadow(0 6px 12px rgba(0, 0, 0, 0.15));
      transition: all 0.3s ease;
    }

    .why-aniko-card .col-md-4:hover img {
      transform: scale(1.1) rotate(5deg);
    }

    .why-aniko-card p {
      color: var(--light-green) !important;
      font-size: 1.1rem;
      line-height: 1.7;
      font-weight: 400;
    }

    .team-section {
      position: relative;
      margin-top: 0;
      background-color: var(--accent-g) !important;
    }

    .team-img {
      width: 100%;
      height: 600px;
      object-fit: cover;
      filter: brightness(0.6) contrast(1.1);
    }

    .team-overlay {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      max-width: 800px;
      padding: 30px 40px;
      text-align: center;
      background: rgba(0, 0, 0, 0.2);
      border-radius: 24px;
      backdrop-filter: blur(10px);
      border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .team-overlay h3 {
      font-family: 'Inter', sans-serif;
      font-weight: 800;
      color: var(--white);
      margin-bottom: 32px;
      text-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
      letter-spacing: -0.02em;
    }

    .team-overlay p {
      color: rgba(255, 255, 255, 0.95);
      line-height: 1.8;
      text-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    .team-members {
      padding: 120px 0;
      background: var(--bg-color);
    }

    .team-member-img {
      width: 200px;
      height: 200px;
      object-fit: cover;
      border: 5px solid var(--white);
      box-shadow: 0 15px 35px rgba(21, 128, 61, 0.2);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
    }

    .team-member-img::before {
      content: '';
      position: absolute;
      inset: -4px;
      background: var(--gradient-accent);
      border-radius: 50%;
      z-index: -1;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .team-member-img:hover::before {
      opacity: 1;
    }

    .team-member-img:hover {
      transform: scale(1.08) rotate(3deg);
      box-shadow: 0 20px 45px rgba(21, 128, 61, 0.3);
    }

    .team-members h5 {
      font-family: 'Inter', sans-serif;
      margin-top: 32px;
      font-size: 1.4rem;
      font-weight: 700;
      color: var(--dark-brown);
    }

    .team-members p {
      color: #6b7280;
      font-size: 1.1rem;
      margin-bottom: 0;
      font-weight: 500;
    }

    .download-img-con {
      background: var(--gradient-primary);
      border-radius: 32px;
      padding: 60px;
      margin-top: 80px;
      box-shadow: 0 20px 40px rgba(21, 128, 61, 0.2);
      position: relative;
      overflow: hidden;
    }

    .download-img-con::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="download-pattern" width="30" height="30" patternUnits="userSpaceOnUse"><circle cx="15" cy="15" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23download-pattern)"/></svg>') repeat;
    }

    .download-img-con img {
      border-radius: 24px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      position: relative;
      z-index: 2;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      25% { transform: translateY(-10px) rotate(1deg); }
      50% { transform: translateY(-5px) rotate(0deg); }
      75% { transform: translateY(-15px) rotate(-1deg); }
    }

    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-40px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(40px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    /* RESPONSIVENSSSS */
    @media (max-width: 768px) {
      .hero {
        padding: 80px 20px 60px;
      }
      
      .farmer-section {
        padding: 80px 20px;
      }
      
      .solutions-row {
        gap: 50px;
      }
      
      .solution-text-side .col-6 {
        flex: 0 0 100%;
        max-width: 100%;
      }
      
      .testimonial-card {
        min-width: 300px;
      }
      
      .why-aniko-card .col-md-4 {
        padding: 30px 20px;
        margin-bottom: 50px;
      }
      
      .why-aniko-card .col-md-4::after {
        display: none;
      }
      
      .why-aniko-card .border-start.border-end {
        border-left: none !important;
        border-right: none !important;
        border-top: 3px solid var(--accent-green) !important;
        border-bottom: 3px solid var(--accent-green) !important;
        padding-top: 50px;
        padding-bottom: 50px;
      }

      .stat-box {
        padding: 40px 25px;
      }

      .benefit-card {
        padding: 40px 25px;
      }
    }

    /* scrollinggg */
    html {
      scroll-behavior: smooth;
    }

    .stat-box,
    .benefit-card,
    .testimonial-card {
      animation: fadeInUp 0.8s ease-out;
    }

    .solution-text-side {
      animation: slideInLeft 0.8s ease-out;
    }

    .solution-img-side {
      animation: slideInRight 0.8s ease-out;
    }

    * {
      will-change: auto;
    }

    .hero-cta,
    .stat-box,
    .benefit-card,
    .testimonial-card,
    .sol-icon,
    .team-member-img {
      will-change: transform;
    }

 /* Floating Circle Button */
.floating-circle {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 60px;
  height: 60px;
  background: var(--light-green);
  color: var(--primary-green);
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 28px;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
  z-index: 1001;
  transition: transform 0.2s ease, background 0.2s ease;
}

.floating-circle:hover {
  transform: scale(1.1);
  background: var(--accent-green);
  color: #fff;
}

/* Side Chat Panel */
.chat-panel {
  position: fixed;
  top: 0;
  right: -350px;
  width: 350px;
  height: 100%;
  background: #fff;
  box-shadow: -3px 0 10px rgba(0,0,0,0.3);
  display: flex;
  flex-direction: column;
  transition: right 0.3s ease;
  z-index: 1000;
}

.chat-panel.open {
  right: 0; 
}


.chat-header {
  background: var(--primary-green);
  color: white;
  padding: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.chat-header button {
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
}


.chat-body {
  flex: 1;
  padding: 10px;
  overflow-y: auto;
  background: #f9f9f9;
}


.chat-footer {
  display: flex;
  border-top: 1px solid #ddd;
}

.chat-footer input {
  flex: 1;
  padding: 10px;
  border: none;
  outline: none;
}

.chat-footer button {
  background: var(--primary-green);
  border: none;
  padding: 10px 15px;
  color: white;
  cursor: pointer;
}

.recommendations {
  background: #f1f8f4;
  border-bottom: 1px solid #ddd;
  padding: 10px;
}

.recommendations p {
  margin: 0 0 8px;
  font-size: 14px;
  color: #333;
}

#suggestions-list {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-bottom: 10px;
}

.suggest-btn {
  padding: 6px 10px;
  font-size: 13px;
  background: var(--light-green);
  color: var(--primary-green);
  border: 1px solid var(--primary-green);
  border-radius: 12px;
  cursor: pointer;
  transition: 0.2s;
}

.suggest-btn:hover {
  background: var(--primary-green);
  color: #fff;
}

.retry-btn {
  display: block;
  width: 100%;
  background: #e0e0e0;
  border: none;
  border-radius: 8px;
  padding: 6px;
  font-size: 13px;
  cursor: pointer;
  transition: 0.2s;
}

.retry-btn:hover {
  background: #ccc;
}

.chat-body {
  flex: 1;
  overflow-y: auto;
  padding: 15px;
  background: #fafafa;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.message {
  max-width: 80%;
  padding: 10px 14px;
  border-radius: 12px;
  line-height: 1.5;
  word-wrap: break-word;
  white-space: pre-wrap; 
  font-size: 14px;
}

.message.user {
  align-self: flex-end;
  background: var(--light-green);
  color: var(--primary-green);
  border: 1px solid var(--primary-green);
}

.message.bot {
  align-self: flex-start;
  background: #f1f1f1;
  color: #333;
  border: 1px solid #ddd;
}
.clear-btn {
  display: block;
  width: 100%;
  background: #e0e0e0;
  border: none;
  border-radius: 8px;
  padding: 6px;
  font-size: 13px;
  cursor: pointer;
  transition: 0.2s;
  margin-top: 8px;
  color: red;
}

.clear-btn:hover {
  background: #ccc;
}


  </style>
</head>

<body>

  <!-- Floating Circle -->
<div id="chatbot-button" class="floating-circle">
  <img src="IMG/logo-notext.png" alt="Chatbot" style="width:25px; height:25px;">
</div>

<!-- Chat Panel -->
<div id="chat-panel" class="chat-panel">
  <div class="chat-header">
    <span>AI Chatbot</span>
    <div class="chat-actions">
      <button id="close-panel" class="close-btn">X</button>
    </div>
  </div>

  <!-- Recommendations -->
  <div id="recommendations" class="recommendations">
    <p>Suggested farming questions:</p>
    <div id="suggestions-list"></div>
    <button id="retry-btn" class="retry-btn">Retry Suggestions</button>
    <button id="clear-history" class="clear-btn">Clear</button>
  </div>

  <!-- Chat Body -->
  <div id="chat-body" class="chat-body"></div>

  <!-- Chat Footer -->
  <div class="chat-footer">
    <input type="text" id="userInput" placeholder="Type a message...">
    <button id="send-btn" class="btn-success">Send</button>
  </div>
</div>


  <!-- HERO SECTION -->
  <div class="hero">
    <div class="container">
      <section class="row align-items-center">
        <div class="col-lg-7 text-center text-lg-start">
          <h1>Smart Soil Monitoring for Modern Farmers</h1>
          <p>Transform your farming with real-time soil insights, AI-powered plant diagnosis, and climate pattern analysis. Join thousands of farmers growing smarter with Aniko.</p>
          <a href="#download" class="hero-cta">
            <span>Download Free App</span>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M7 17L17 7M17 7H7M17 7V17"/>
            </svg>
          </a>
        </div>
        <div class="col-lg-5 text-center mt-4 mt-lg-0">
          <img src="IMG/google-play.png" alt="Download on Google Play" class="hero-img">
        </div>
      </section>
    </div>
  </div>

  <div class="container">
    <div class="home-img-container">
      <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Aniko App Interface" class="img-fluid">
    </div>

    <p class="subtext" id="about">
      Aniko revolutionizes agriculture with intelligent soil monitoring technology. Track moisture, temperature, 
      sunlight, and humidity in real-time while getting AI-powered insights for healthier crops and maximum yields.
    </p>

    <hr class="custom-line">
    <h2 class="section-heading">Trusted by Farmers Worldwide</h2>

    <p class="section-subtext">
      Discover how Aniko is transforming agriculture with data-driven insights, from real-time monitoring to improved crop yields.
    </p>

    <div class="row stats-section">
      <div class="col-md-4 col-12 mb-4">
        <div class="stat-box">
          <img src="IMG/soil-monitoring-icon.png" alt="Soil Monitoring">
          <p>24/7 Continuous Soil Health Monitoring with Real-Time Alerts</p>
        </div>
      </div>

      <div class="col-md-4 col-12 mb-4">
        <div class="stat-box">
          <img src="IMG/plant-treatment-icon.png" alt="Plant Treatment">
          <p>AI-Powered Diagnosis for 780+ Plant Diseases with Treatment Recommendations</p>
        </div>
      </div>

      <div class="col-md-4 col-12 mb-4">
        <div class="stat-box">
          <img src="IMG/climate-icon.png" alt="Climate Analysis">
          <p>Advanced Climate Pattern Analysis Detecting 5+ Weather Anomalies</p>
        </div>
      </div>
    </div>
  </div>


  <!-- SOLUTION / BENEFITS SECTION -->
  <section class="farmer-section" id="features">
    <div class="container">
      <h1>Precision Agriculture Made Simple</h1>
      
      <div class="solutionBenefits-con mt-5">
        <div class="row align-items-center solutions-row">
          <div class="col-lg-6 solution-text-side">
            <h3>Aniko</h3>
            <p>Advanced Features for Smart Farming</p>

            <div class="row g-4">
              <div class="col-6 d-flex align-items-center">
                <img src="IMG/fc1.png" alt="Climate Analysis" class="me-3 sol-icon" style="width:45px; height:45px;">
                <p class="mb-0">Climate Pattern Analysis</p>
              </div>

              <div class="col-6 d-flex align-items-center">
                <img src="IMG/fc2.png" alt="Plant Diagnosis" class="me-3 sol-icon" style="width:45px; height:45px;">
                <p class="mb-0">AI-Powered Plant Diagnosis</p>
              </div>

              <div class="col-6 d-flex align-items-center">
                <img src="IMG/fc3.png" alt="Soil Health" class="me-3 sol-icon" style="width:45px; height:45px;">
                <p class="mb-0">Real-Time Soil Monitoring</p>
              </div>

              <div class="col-6 d-flex align-items-center">
                <img src="IMG/fc4.png" alt="Health Check" class="me-3 sol-icon" style="width:45px; height:45px;">
                <p class="mb-0">Intelligent Health Analytics</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 text-center mt-4 mt-lg-0 solution-img-side">
            <img src="<?php echo $benefitsImage; ?>" alt="Aniko App Interface" class="img-fluid" style="max-width:380px;">
          </div>
        </div>

        <hr class="custom-line">

        <div class="row mb-4 benefits-row">
          <div class="col-lg-10 solution-text-side">
            <h3>Aniko</h3>
            <p>Proven Benefits for Your Farm</p>

            <div class="row g-4">
              <div class="col-md-4 col-12">
                <div class="benefit-card">
                  <h5>Monitor & Protect</h5>
                  <img src="IMG/benefits-icon1.png" alt="24/7 Monitoring">
                  <p>Continuous field monitoring with instant alerts for optimal crop protection and growth management.</p>
                </div>
              </div>

              <div class="col-md-4 col-12">
                <div class="benefit-card">
                  <h5>Predict & Prevent</h5>
                  <img src="IMG/benefits-icon2.png" alt="Climate Prediction">
                  <p>Advanced climate anomaly prediction helps you prepare and protect your crops from weather threats.</p>
                </div>
              </div>

              <div class="col-md-4 col-12">
                <div class="benefit-card">
                  <h5>Optimize & Grow</h5>
                  <img src="IMG/benefits-icon3.png" alt="AI Features">
                  <p>AI-powered insights and recommendations to maximize yield and optimize resource usage efficiently.</p>
                </div>
              </div>
            </div>
          </div>
        </div>

       
      </div>
    </div>
  </section>

    <!-- TESTIMONIAL SECTIONNNN -->
  <section class="testimonial-section py-5">
    <div class="container">
      <div class="row align-items-center mb-4">
        <div class="col-lg-8">
          <h2 class="fw-bold text-dark" id="download">What Our Farmers Say</h2>
          <p class="text-muted mb-0">Real experiences from real farmers who are growing smarter with Aniko.</p>
        </div>

        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
          <?php
          if (isset($_SESSION['email'])) {
            $button_link = "testimonial-submit.php";
          } else {
            $button_link = htmlspecialchars($login_url) . "&redirect=testimonial-submit";
          }
          ?>
          <a href="<?php echo $button_link; ?>" class="btn submit-btn">Submit Now!</a>
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
              echo '        <h6 class="mb-0 testimonial-name">' . htmlspecialchars($row['name']) . '</h6>';
              echo '        <small class="text-muted testimonial-email">' . htmlspecialchars($row['email']) . '</small>';
              echo '      </div>';
              echo '    </div>';
              echo '    <p class="card-text testimonial-text">' . htmlspecialchars($row['testimonial']) . '</p>';
              echo '  </div>';
              echo '  <div class="card-footer text-muted">';
              echo '    <small>Posted on ' . date("F j, Y", strtotime($row['created_at'])) . '</small>';
              echo '  </div>';
              echo '</div>';
            }
          } else {
            echo '<p class="text-muted">No approved testimonials yet.</p>';
          }
        ?>
      </div>

      <button class="viewTestimonial-btn"><a href="testimonial-display.php">View Testimonials</a></button>

      <?php
        $result = $con->query("SELECT * FROM download_images ORDER BY uploaded_at DESC LIMIT 1");
        if ($row = $result->fetch_assoc()):
      ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- WHY ANIKO SECTION -->
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
          <?php
          $res = $con->query("SELECT image_path FROM why_aniko_images ORDER BY uploaded_at DESC LIMIT 1");
          if ($row = $res->fetch_assoc()):
          ?>
          <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Why Aniko" class="img-fluid rounded">
          <?php else: ?>
          <p>No Why Aniko image uploaded yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <div class="card mt-5 why-aniko-card">
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

  <!-- TEAM TEXT SECTIONNNN -->
  <section class="team-section position-relative" id="team">
    <div class="container-fluid p-0">
      <img src="IMG/team-image.png" alt="Our Team" class="img-fluid team-img w-100">
      <div class="team-overlay text-center text-white">
        <h3 class="fw-bold">Meet the Team</h3>
        <p class="lead">We are five 3rd-year IT students who share a passion for technology 
        and innovation, each bringing unique skills and perspectives to create 
        impactful, real-world solutions together.</p>
      </div>
    </div>
  </section>

<!-- TEAM SECTION -->
  <section class="team-members py-5">
    <div class="container text-center">
      <div class="row justify-content-center">
        <?php
        $result = $con->query("SELECT * FROM team_members ORDER BY uploaded_at DESC");
        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-md-4 mb-4">
          <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="rounded-circle mb-3 team-member-img" style="width:150px; height:150px; object-fit:cover;">
          <h5 class="fw-bold"><?= htmlspecialchars($row['name']) ?></h5>
          <p class="text-muted"><?= htmlspecialchars($row['role']) ?></p>
        </div>
        <?php endwhile; else: ?>
        <p>No team members added yet.</p>
        <?php endif; ?>
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
  <script>
document.addEventListener("DOMContentLoaded", () => {
  // Elements
  const chatbotBtn = document.getElementById("chatbot-button");
  const chatPanel = document.getElementById("chat-panel");
  const closePanel = document.getElementById("close-panel");
  const sendBtn = document.getElementById("send-btn");
  const chatBody = document.getElementById("chat-body");
  const chatInput = document.getElementById("userInput"); // fixed ID
  const retryBtn = document.getElementById("retry-btn");
  const suggestionsList = document.getElementById("suggestions-list");
  const clearBtn = document.getElementById("clear-history");

const STORAGE_KEY = "chatHistory_" + currentUser;  // unique per user
let chatHistory = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];


  // Open/close panel
  chatbotBtn.addEventListener("click", () => {
    chatPanel.classList.add("open");
    chatbotBtn.style.display = "none";
    renderChatHistory();
    renderSuggestions();
  });

  closePanel.addEventListener("click", () => {
    chatPanel.classList.remove("open");
    chatbotBtn.style.display = "flex";
  });

  // Append message
  function appendMessage(sender, text, save = true) {
    const msg = document.createElement("div");
    msg.classList.add("message", sender === "You" ? "user" : "bot");
    msg.innerText = text.trim();
    chatBody.appendChild(msg);
    chatBody.scrollTop = chatBody.scrollHeight;

    if (save) {
      chatHistory.push({ sender, text });
      localStorage.setItem(STORAGE_KEY, JSON.stringify(chatHistory));
    }
  }

  // Render chat history
  function renderChatHistory() {
    chatBody.innerHTML = "";
    chatHistory.forEach(msg => appendMessage(msg.sender, msg.text, false));
  }

  // Clear history
  clearBtn.addEventListener("click", () => {
    localStorage.removeItem(STORAGE_KEY);
    chatHistory = [];
    renderChatHistory();
  });

 async function getAIResponse(userMsg) {
  appendMessage("Bot", "Thinking...", false);

  try {
    // Build message history with a farming-only system instruction
    const messages = [
      {
        role: "system",
        content: "You are Aniko, an AI farming assistant. You ONLY answer farming-related questions. If a user asks something unrelated to farming, politely say: 'I specialize in farming topics. Can you ask me something about crops, livestock, soil, or agriculture?'"
      },
      ...chatHistory.map(m => ({
        role: m.sender === "You" ? "user" : "assistant",
        content: m.text
      })),
      { role: "user", content: userMsg }
    ];

    const res = await fetch("https://openrouter.ai/api/v1/chat/completions", {
      method: "POST",
      headers: {
        "HTTP-Referer": "http://localhost", // change to your site
        "X-Title": "Aniko Chatbot",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        model: "deepseek/deepseek-r1:free",
        messages: messages
      })
    });

    const data = await res.json();
    const reply = data.choices?.[0]?.message?.content || "No response from AI.";

    chatBody.lastChild.remove(); // remove "Thinking..."
    appendMessage("Bot", reply);

  } catch (err) {
    chatBody.lastChild.remove();
    appendMessage("Bot", " Error: " + err.message);
  }
}



  // Send message
  function sendMessage() {
    const userMsg = chatInput.value.trim();
    if (!userMsg) return;
    appendMessage("You", userMsg);
    chatInput.value = "";
    getAIResponse(userMsg);
  }

  sendBtn.addEventListener("click", sendMessage);
  chatInput.addEventListener("keydown", (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  // ---------------- Suggestions ----------------
  const allSuggestions = [
    "How to improve rice yield?",
    "Best fertilizer for corn?",
    "How to prevent crop diseases?",
    "What are sustainable farming practices?",
    "How to save water in irrigation?",
    "Best organic pesticides?",
    "How to grow vegetables in dry soil?",
    "When is the best season to plant rice?",
    "Tips for greenhouse farming?",
    "How to raise chickens for eggs?",
    "How to manage livestock health?",
    "What crops are profitable in small farms?",
    "How to start organic farming?",
    "Best practices for crop rotation?",
    "How to prevent soil erosion?",
    "How to use compost effectively?",
    "How to deal with weeds naturally?",
    "How to increase banana production?",
    "Best practices for mango farming?",
    "How to improve soil fertility naturally?",
    "How to manage farm expenses?",
    "Best modern farming technologies?",
    "How does climate change affect farming?",
    "Best practices for sustainable livestock?",
    "How to market farm products effectively?",
  ];

  function getRandomSuggestions(num = 5) {
    return [...allSuggestions].sort(() => 0.5 - Math.random()).slice(0, num);
  }

  function renderSuggestions() {
    suggestionsList.innerHTML = "";
    getRandomSuggestions(5).forEach(suggestion => {
      const btn = document.createElement("button");
      btn.className = "suggest-btn";
      btn.textContent = suggestion;
      btn.addEventListener("click", () => {
        appendMessage("You", suggestion);
        getAIResponse(suggestion);
      });
      suggestionsList.appendChild(btn);
    });
  }

  retryBtn.addEventListener("click", renderSuggestions);
  renderSuggestions();
});
</script>

<!-- Floating Button Drag -->
<script>
const btn = document.getElementById('chatbot-button');
let isDragging = false, offsetX, offsetY;

btn.addEventListener('mousedown', (e) => {
  isDragging = true;
  offsetX = e.clientX - btn.getBoundingClientRect().left;
  offsetY = e.clientY - btn.getBoundingClientRect().top;
  btn.style.transition = 'none';
});

document.addEventListener('mousemove', (e) => {
  if (!isDragging) return;
  let left = e.clientX - offsetX;
  let top = e.clientY - offsetY;
  const maxLeft = window.innerWidth - btn.offsetWidth;
  const maxTop = window.innerHeight - btn.offsetHeight;
  if (left < 0) left = 0;
  if (top < 0) top = 0;
  if (left > maxLeft) left = maxLeft;
  if (top > maxTop) top = maxTop;
  btn.style.left = left + 'px';
  btn.style.top = top + 'px';
});

document.addEventListener('mouseup', () => {
  if (isDragging) {
    isDragging = false;
    btn.style.transition = 'transform 0.2s ease, background 0.2s ease';
  }
});
</script>
<script>
  const currentUser = "<?php echo $_SESSION['email']; ?>"; 
</script>








</body>
</html>
