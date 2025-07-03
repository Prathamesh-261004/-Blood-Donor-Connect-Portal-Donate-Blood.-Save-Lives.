<?php
require 'db.php';
$total_donors = $db->query("SELECT COUNT(*) FROM donors")->fetchColumn();
$verified_donors = $db->query("SELECT COUNT(*) FROM donors WHERE verified=1")->fetchColumn();
$total_requests = $db->query("SELECT COUNT(*) FROM blood_requests")->fetchColumn();
$total_donations = $db->query("SELECT COUNT(*) FROM donation_history")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donor Connect - Save Lives Together</title>
    <meta name="description" content="Connect blood donors with patients in need. Join our verified community of life-savers.">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-red: #b30000;
            --dark-red: #7a0000;
            --light-red: #d00000;
            --bg-light: #fff0f0;
            --bg-card: #ffe6eb;
            --text-dark: #333;
            --text-light: #555;
            --shadow: 0 5px 15px rgba(0,0,0,0.1);
            --shadow-hover: 0 8px 25px rgba(0,0,0,0.15);
            --border-radius: 15px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            scroll-behavior: smooth;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background: var(--primary-red);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow);
        }

        .navbar-brand {
            font-size: 22px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hamburger {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 5px;
        }

        .hamburger-bar {
            width: 25px;
            height: 3px;
            background: white;
            transition: var(--transition);
            border-radius: 2px;
        }

        .hamburger.active .hamburger-bar:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger.active .hamburger-bar:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active .hamburger-bar:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* Sidebar Menu */
        .sidebar {
            position: fixed;
            top: 0;
            right: -250px;
            background: var(--light-red);
            color: white;
            width: 250px;
            height: 100vh;
            padding: 80px 20px 20px;
            transition: right 0.4s ease;
            z-index: 99;
            box-shadow: var(--shadow);
        }

        .sidebar.active {
            right: 0;
        }

        .sidebar-link {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: var(--transition);
        }

        .sidebar-link:hover {
            color: #ffcccc;
            transform: translateX(5px);
        }

        /* Hero Section */
        .hero {
            padding-top: 80px;
            text-align: center;
            background: linear-gradient(135deg, #ffcccc 0%, #ffe6eb 100%);
            padding: 80px 20px 60px;
            animation: fadeInDown 1s ease-out;
        }

        .hero h1 {
            font-size: clamp(28px, 5vw, 42px);
            color: var(--primary-red);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .hero p {
            color: var(--text-dark);
            font-size: clamp(16px, 3vw, 20px);
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            background: var(--light-red);
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: var(--shadow);
        }

        .btn:hover {
            background: var(--primary-red);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        /* Stats Section */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            padding: 60px 20px;
            background: var(--bg-card);
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-card {
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
            opacity: 0;
            transform: scale(0.8);
            animation: zoomIn 0.6s ease forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            color: var(--primary-red);
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--text-light);
            text-transform: uppercase;
            font-weight: 500;
            letter-spacing: 1px;
        }

        /* Testimonials Section */
        .testimonials {
            background: white;
            padding: 60px 20px;
            text-align: center;
        }

        .testimonials h2 {
            color: var(--primary-red);
            margin-bottom: 30px;
            font-size: clamp(24px, 4vw, 32px);
        }

        .testimonial-container {
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .testimonial-slide {
            font-style: italic;
            color: var(--text-dark);
            font-size: 18px;
            line-height: 1.8;
            padding: 20px;
            background: var(--bg-light);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            opacity: 0;
            transition: opacity 0.5s ease;
            position: absolute;
            width: 100%;
        }

        .testimonial-slide.active {
            opacity: 1;
        }

        .testimonial-slide strong {
            color: var(--primary-red);
            font-weight: 600;
        }

        /* Contact Section */
        .contact {
            background: var(--primary-red);
            color: white;
            text-align: center;
            padding: 60px 20px;
        }

        .contact h2 {
            font-size: clamp(24px, 4vw, 32px);
            margin-bottom: 15px;
        }

        .contact p {
            font-size: 18px;
            max-width: 500px;
            margin: 0 auto;
        }

        /* Footer */
        .footer {
            background: var(--dark-red);
            color: white;
            text-align: center;
            padding: 40px 20px;
        }

        .footer-quote {
            font-style: italic;
            font-size: 18px;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .footer-copyright {
            font-size: 14px;
            opacity: 0.8;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 12px 15px;
            }

            .navbar-brand {
                font-size: 18px;
            }

            .hero {
                padding: 60px 15px 40px;
            }

            .stats {
                padding: 40px 15px;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }

            .stat-card {
                padding: 20px;
            }

            .sidebar {
                width: 280px;
                right: -280px;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                min-width: 200px;
                justify-content: center;
            }
        }

        /* Accessibility */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }

        /* Focus styles for keyboard navigation */
        .btn:focus,
        .sidebar-link:focus {
            outline: 3px solid #ffcccc;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <span>🩸</span>
            <span>Blood Donor Connect</span>
        </div>
        <div class="hamburger" onclick="toggleMenu()" id="hamburger" aria-label="Toggle menu">
            <div class="hamburger-bar"></div>
            <div class="hamburger-bar"></div>
            <div class="hamburger-bar"></div>
        </div>
    </nav>

    <!-- Sidebar Menu -->
    <aside class="sidebar" id="sidebar">
        <a href="#home" class="sidebar-link" onclick="closeMenu()">
            <span>🏠</span>
            <span>Home</span>
        </a>
        <a href="#stats" class="sidebar-link" onclick="closeMenu()">
            <span>📊</span>
            <span>Stats</span>
        </a>
        <a href="#testimonials" class="sidebar-link" onclick="closeMenu()">
            <span>💬</span>
            <span>Testimonials</span>
        </a>
        <a href="#contact" class="sidebar-link" onclick="closeMenu()">
            <span>📞</span>
            <span>Contact</span>
        </a>
        <a href="register.php" class="sidebar-link">
            <span>📝</span>
            <span>Register</span>
        </a>
        <a href="search.php" class="sidebar-link">
            <span>🔍</span>
            <span>Search</span>
        </a>
        <a href="smart_search.php" class="sidebar-link">
            <span>📍</span>
            <span>Pincode Search</span>
        </a>
        <a href="login.php" class="sidebar-link">
            <span>🔐</span>
            <span>Login</span>
        </a>
    </aside>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <h1>Together We Save Lives</h1>
        <p>Connecting Donors & Patients with Speed and Trust</p>
        <div class="hero-buttons">
            <a href="register.php" class="btn">
                <span>📝</span>
                <span>Register as Donor</span>
            </a>
            <a href="search.php" class="btn">
                <span>🔍</span>
                <span>Find Donors</span>
            </a>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="stats">
        <?php
        $stats = [
            ["👥", $total_donors, "Total Donors"],
            ["✅", $verified_donors, "Verified Donors"],
            ["📬", $total_requests, "Blood Requests"],
            ["🩸", $total_donations, "Successful Donations"]
        ];
        
        foreach ($stats as [$icon, $number, $label]):
        ?>
            <div class="stat-card">
                <div class="stat-icon"><?= $icon ?></div>
                <div class="stat-number"><?= number_format($number) ?></div>
                <div class="stat-label"><?= $label ?></div>
            </div>
        <?php endforeach; ?>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <h2>💬 What Our Users Say</h2>
        <div class="testimonial-container">
            <div class="testimonial-slide active">
                "This platform helped me save my father's life in hours! The verification system gave us confidence in finding reliable donors."
                <br><strong>– Priya S., Mumbai</strong>
            </div>
            <div class="testimonial-slide">
                "Easy to use and verified donors. The search feature is amazing. Highly recommend to everyone!"
                <br><strong>– Aditya M., Delhi</strong>
            </div>
            <div class="testimonial-slide">
                "A beautiful initiative for society. The interface is clean and the response time is incredible. Keep it up!"
                <br><strong>– Fatima A., Bangalore</strong>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <h2>📞 Get Involved</h2>
        <p>Join our community of life-savers. Create an account, post a request, or help spread the word about this life-saving initiative!</p>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-quote">
            "You don't have to be a doctor to save lives. Just donate blood."
        </div>
        <div class="footer-copyright">
            © <?= date('Y') ?> Blood Donor Connect. Saving lives, one donation at a time.
        </div>
    </footer>

    <script>
        // Menu toggle functionality
        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');
            
            sidebar.classList.toggle('active');
            hamburger.classList.toggle('active');
        }

        function closeMenu() {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');
            
            sidebar.classList.remove('active');
            hamburger.classList.remove('active');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const hamburger = document.getElementById('hamburger');
            
            if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                closeMenu();
            }
        });

        // Testimonials slider
        function initTestimonials() {
            const slides = document.querySelectorAll('.testimonial-slide');
            let currentIndex = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % slides.length;
                showSlide(currentIndex);
            }

            // Auto-advance testimonials
            setInterval(nextSlide, 5000);
        }

        // Smooth scrolling for navigation links
        function initSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initTestimonials();
            initSmoothScroll();
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });
    </script>
</body>
</html>