<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
?>
<?php include 'INCLUDE/header-logged.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Testimonial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

        body {
            background: var(--c1);
            margin: 0;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .testimonial-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
          
        }

        .testimonial-card {
              background: var(--c9);
          
            border-radius: 24px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.12);
        }

        .content-section {
            padding-right: 40px;
        }

        .testimonial-card h2 {
            font-weight: 700;
            font-size: 2.5rem;
            color: var(--c7);
            margin-bottom: 20px;
            line-height: 1.2;
            position: relative;
        }

        .subtitle {
            font-size: 1.125rem;
             color: var(--c7);
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
           color: var(--c7);
            font-size: 0.95rem;
        }

        .feature-list li i {
            color: var(--c7);
            margin-right: 12px;
            font-size: 1rem;
        }

        .form-section {
            background: var(--c6); 
            border-radius: 20px;
            padding: 40px;
            
            color: var(--c4);
        }

        .form-label {
            font-weight: 600;
            color: var(--c7);
            margin-bottom: 12px;
            display: block;
            font-size: 0.95rem;
        }

        .testimonial-card textarea {
            width: 100%;
            border-radius: 16px;
          
            padding: 20px;
            font-size: 1rem;
            resize: vertical;
            transition: all 0.3s ease;
            background: var(--c7);
            color: var(--c5);
            font-family: inherit;
            line-height: 1.6;
            min-height: 180px;
        }

        .testimonial-card textarea:focus {
            outline: none;
            border-color: var(--c4);
            box-shadow: 0 0 0 4px rgba(77, 45, 24, 0.2);
            background: var(--c7);
        }

        .testimonial-card textarea::placeholder {
            color: #9ca3af;
            font-style: italic;
        }

        .submit-btn {
            background: var(--c9); 
            color: var(--c7);
            font-weight: 600;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(77, 45, 24, 0.3);
        }

        .submit-btn:hover {
            background: var(--c6);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(77, 45, 24, 0.4);
        }

        .character-count {
            font-size: 0.875rem;
            color: var(--c7);
            text-align: right;
            margin-top: 8px;
        }

        @media (max-width: 992px) {
            .testimonial-card {
                padding: 40px;
            }
            
            .content-section {
                padding-right: 0;
                margin-bottom: 40px;
            }
            
            .testimonial-card h2 {
                font-size: 2.25rem;
            }
        }

        @media (max-width: 768px) {
            .testimonial-card {
                padding: 30px 20px;
                margin: 20px;
            }
            
            .form-section {
                padding: 30px 20px;
            }
            
            .testimonial-card h2 {
                font-size: 2rem;
            }
        }

       
        .form-section {
            animation: slideInUp 0.6s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<br><br><br>
<div class="testimonial-container">
    <div class="testimonial-card">
        <div class="row g-0">
            <div class="col-lg-5 d-flex flex-column justify-content-center">
                <div class="content-section">
                    <h2>Share Your Experience</h2>
                    <p class="subtitle">Your feedback is valuable! Tell us how Aniko has helped improve your farming practices and let others learn from your experience.</p>
                    
                    <ul class="feature-list">
                        <li><i class="fas fa-check-circle"></i> Help other farmers learn from your success</li>
                        <li><i class="fas fa-users"></i> Build a stronger farming community</li>
                        <li><i class="fas fa-star"></i> Share your authentic experience</li>
                        <li><i class="fas fa-heart"></i> Inspire others to grow better</li>
                    </ul>
                </div>
            </div>

          
            <div class="col-lg-7">
                <div class="form-section">
                    <form action="save_testimonial.php" method="POST">
                        <label class="form-label" for="testimonial">
                            <i class="fas fa-quote-left"></i> Your Testimonial
                        </label>
                        <textarea 
                            name="testimonial" 
                            id="testimonial"
                            rows="7" 
                            placeholder="Share your story... How has Aniko transformed your farming experience? What specific benefits have you seen?" 
                            required
                            maxlength="1000"
                            oninput="updateCharCount(this)">
                        </textarea>
                        <div class="character-count" id="charCount">0 / 1000 characters</div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane"></i>
                                Submit Testimonial
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCharCount(textarea) {
        const charCount = document.getElementById('charCount');
        const current = textarea.value.length;
        const max = textarea.getAttribute('maxlength');
        charCount.textContent = `${current} / ${max} characters`;
        
        if (current > max * 0.9) {
            charCount.style.color = '#ef4444';
        } else if (current > max * 0.7) {
            charCount.style.color = '#f59e0b';
        } else {
            charCount.style.color = '#6b7280';
        }
    }
</script>
</body>
</html>
