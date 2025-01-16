<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HIKSROT - Esports Team</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #0a0a0a;
            color: #ffffff;
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Hero Section */
        .hero {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 40px;
            margin-bottom: 80px;
            background: linear-gradient(45deg, #1a1a1a, #2a2a2a);
            padding: 40px;
            border-radius: 20px;
        }

        .hero-content {
            flex: 1;
        }

        .hero-content h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            line-height: 1.2;
            color: #4834d4;
        }

        .brand-name {
            color: #4834d4;
            font-weight: 700;
        }

        .hero-content p {
            color: #cccccc;
            margin-bottom: 30px;
        }

        .hero-image {
            flex: 1;
            text-align: right;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        /* Features Section */
        .features {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-bottom: 80px;
        }

        .feature-card {
            background: #1a1a1a;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #4834d4;
            transition: transform 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 20px;
            filter: invert(1);
        }

        .feature-card h3 {
            margin-bottom: 15px;
            color: #4834d4;
        }

        .feature-card p {
            color: #cccccc;
            font-size: 0.9rem;
        }

        /* How It Works Section */
        .how-it-works {
            text-align: center;
            margin-bottom: 80px;
        }

        .how-it-works h2 {
            font-size: 2rem;
            margin-bottom: 50px;
            color: #4834d4;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .step {
            padding: 20px;
            background: #1a1a1a;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .step:hover {
            transform: scale(1.05);
        }

        .step img {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .step h3 {
            margin-bottom: 15px;
            color: #4834d4;
        }

        .step p {
            color: #cccccc;
            font-size: 0.9rem;
        }

        /* Footer */
        .footer {
            background: #1a1a1a;
            padding: 40px 0;
            margin-top: 80px;
            border-top: 1px solid #4834d4;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-logo img {
            width: 40px;
            height: 40px;
        }

        .contact-info {
            text-align: right;
            color: #cccccc;
        }

        .social-links {
            margin-top: 20px;
            text-align: center;
        }

        .social-links a {
            margin: 0 10px;
            color: #4834d4;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: #cccccc;
        }

        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }

            .features {
                grid-template-columns: 1fr;
            }

            .steps {
                grid-template-columns: 1fr;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .contact-info {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Custom furniture crafted for you with <span class="brand-name">IHKEA</span></h1>
                <p>Crafting custom furniture to transform your space, blending functionality with personalized style. Every piece is meticulously designed and built to meet your unique needs and preferences, ensuring both quality and aesthetic appeal.</p>
            </div>
            <div class="hero-image">
                <img src="/api/placeholder/500/400" alt="Custom Furniture Boxes">
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="feature-card">
                <img src="/api/placeholder/60/60" alt="Design Icon" class="feature-icon">
                <h3>Design Customization</h3>
                <p>Select styles, materials, colors, and dimensions to create unique furniture pieces that fit their specifications and taste.</p>
            </div>
            <div class="feature-card">
                <img src="/api/placeholder/60/60" alt="3D Icon" class="feature-icon">
                <h3>3D Visualization</h3>
                <p>Visualize the final product and make any necessary adjustments before placing an order.</p>
            </div>
            <div class="feature-card">
                <img src="/api/placeholder/60/60" alt="Tracking Icon" class="feature-icon">
                <h3>Order Tracking</h3>
                <p>Track the progress of furniture orders from design approval to delivery, ensuring transparency & timely updates.</p>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="how-it-works">
            <h2>How <span class="brand-name">IHKEA</span> works</h2>
            <div class="steps">
                <div class="step">
                    <img src="/api/placeholder/120/120" alt="Create Account">
                    <h3>Create an account</h3>
                    <p>Sign up to save your designs, manage orders, and receive updates.</p>
                </div>
                <div class="step">
                    <img src="/api/placeholder/120/120" alt="Customize Furniture">
                    <h3>Customize Your Furniture</h3>
                    <p>Personalize every aspect of your chosen design, from materials to dimensions.</p>
                </div>
                <div class="step">
                    <img src="/api/placeholder/120/120" alt="Track Order">
                    <h3>Track Your Order</h3>
                    <p>Stay updated with the status of your order from the moment it's confirmed.</p>
                </div>
                <div class="step">
                    <img src="/api/placeholder/120/120" alt="Delivery">
                    <h3>Enjoy Delivery</h3>
                    <p>Benefit from our reliable delivery service, with optional assembly assistance.</p>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="/api/placeholder/40/40" alt="IHKEA Logo">
                    <h3>IHKEA</h3>
                </div>
                <div class="contact-info">
                    <p>📍 UNVKDLS Surabaya</p>
                    <p>📞 +62 896725960</p>
                    <p>📧 +62 896725960</p>
                </div>
            </div>
            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
                <a href="#">LinkedIn</a>
                <a href="#">YouTube</a>
                <a href="#">Instagram</a>
                <a href="#">Google+</a>
                <a href="#">Pinterest</a>
                <a href="#">RSS</a>
            </div>
        </div>
    </footer>
</body>
</html>