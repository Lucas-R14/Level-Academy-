<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inside Out - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .insideout-hero {
            background: #232323;
            padding: 6rem 0 4rem 0;
        }
        .insideout-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.7rem;
            color: #fff !important;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 0;
            margin-bottom: 3.5rem;
            background: none !important;
            -webkit-background-clip: initial !important;
            background-clip: initial !important;
            -webkit-text-fill-color: #fff !important;
            animation: none !important;
        }
        .insideout-hero-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4rem;
            width: 100%;
            padding-left: 1vw;
            padding-right: 1vw;
            margin: 0;
            flex-wrap: wrap;
        }
        .insideout-hero-img {
            min-width: 320px;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            overflow: hidden;
            background: #6c3be4;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 40px 0 rgba(175,4,232,0.15);
        }
        .insideout-hero-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .insideout-hero-text {
            flex: 1;
            color: #fff;
            font-family: 'Exo 2', sans-serif;
            max-width: 600px;
        }
        .insideout-hero-text .about-title {
            color: #888;
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            font-size: 1rem;
            letter-spacing: 0.2em;
            margin-bottom: 1.2rem;
        }
        .insideout-hero-text p {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 1.2rem;
            line-height: 1.6;
            font-weight: 500;
        }
        .insideout-hero-text p:last-child {
            margin-bottom: 0;
        }
        /* Separator Line */
        .programs-separator {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
            background: #000;
        }
        .programs-separator:before {
            content: "";
            height: 1px;
            background: linear-gradient(to right, rgba(255,255,255,0), rgba(255,255,255,0.2), rgba(255,255,255,0));
            flex-grow: 1;
            margin-right: 1.5rem;
        }
        .programs-separator:after {
            content: "";
            height: 1px;
            background: linear-gradient(to left, rgba(255,255,255,0), rgba(255,255,255,0.2), rgba(255,255,255,0));
            flex-grow: 1;
            margin-left: 1.5rem;
        }
        .programs-separator-text {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.1rem;
            color: #6c3be4;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .programs-separator-icon {
            font-size: 1.2rem;
            color: #6c3be4;
        }
        /* Programs Images Only */
        .programs-section {
            background: #000;
            padding: 4rem 0 4rem 0;
        }
        .programs-images {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 3vw;
            flex-wrap: wrap;
            width: 100%;
            padding-left: 1vw;
            padding-right: 1vw;
        }
        .program-img {
            width: 440px;
            max-width: 98vw;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 24px 0 rgba(0,0,0,0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .program-img img {
            width: 100%;
            height: auto;
            display: block;
        }
        @media (max-width: 1100px) {
            .programs-images {
                flex-direction: column;
                align-items: center;
                gap: 2rem;
            }
            .program-img {
                width: 98vw;
                max-width: 98vw;
            }
        }
        
        /* Buttons Section */
        .buttons-section {
            background: #000;
            padding: 2rem 0 5rem 0;
            text-align: center;
        }
        .buttons-container {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .action-button {
            display: inline-block;
            background: #f2f2f2;
            color: #333;
            text-decoration: none;
            padding: 1rem 6rem;
            border-radius: 2rem;
            font-family: 'Exo 2', sans-serif;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            min-width: 280px;
            text-align: center;
        }
        .action-button:hover {
            background: #e0e0e0;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        /* Contact Section */
        .contact-section {
            background: #000;
            padding: 1rem 0 4rem 0;
            text-align: center;
        }
        .contact-text {
            font-family: 'Exo 2', sans-serif;
            color: #fff;
            font-size: 1.8rem;
            font-weight: 400;
        }
    </style>
</head>
<body>
<?php
// Incluindo o header
include '../components/header.php';
?>

<div class="insideout-hero">
    <h1 class="insideout-title">Inside Out Summer Camp 2025</h1>
    <div class="insideout-hero-content">
        <div class="insideout-hero-img">
            <img src="../../public/assets/images/insideout/inside-out.png" alt="Inside Out Summer Camp 2025">
        </div>
        <div class="insideout-hero-text">
            <div class="about-title">about</div>
            <p>Our camp is meticulously designed by professionals across the educational spectrum, ensuring a robust and enriching learning experience. With state-of-the-art equipment and small class sizes, we provide a hands-on, explorative environment where every camper gets personalized attention. This unique setup helps nurture not only tech skills but also life skills, using gaming as a tool for real-world applications.</p>
            <p>The Inside/Out Summer Camp, organized by Level Academy, offers an exciting, immersive experience for gaming enthusiasts aged 6+. Spanning eight weeks, this comprehensive summer camp combines Esports Athlete Training, Game Creation, Outdoor Physical Activities, Content Creation, and Professional Coaching to provide a unique and enriching experience for participants.</p>
            <p>See bellow for the pricing and course guide and the links for registration as well as the esports summer camp 2025 brochure.</p>
        </div>
    </div>
</div>

<!-- Separator Line -->
<div class="programs-separator">
    <div class="programs-separator-text">
        <i class="fas fa-gamepad programs-separator-icon"></i> Programs
    </div>
</div>

<!-- Programs Images Only -->
<section class="programs-section">
    <div class="programs-images">
        <div class="program-img">
            <img src="../../public/assets/images/insideout/2day.png" alt="2 Day Programme">
        </div>
        <div class="program-img">
            <img src="../../public/assets/images/insideout/3day.png" alt="3 Day Programme">
        </div>
        <div class="program-img">
            <img src="../../public/assets/images/insideout/5day.png" alt="5 Day Programme">
        </div>
    </div>
</section>

<!-- Buttons Section -->
<div class="buttons-section">
    <div class="buttons-container">
        <a href="https://levelacademy.com.mt/wp-content/uploads/2025/02/InsideOut-2025-Brochure-1.pdf" target="_blank" class="action-button">Brochure</a>
        <a href="https://docs.google.com/forms/d/e/1FAIpQLScmLD0PYxSiNooGa-GAfX0nql8zHrHoUwT_VeozdbJ-jHu-7Q/viewform" target="_blank" class="action-button">Register</a>
    </div>
</div>

<!-- Contact Section -->
<div class="contact-section">
    <h2 class="contact-text">Contact us here: info@levelacademy.com.mt</h2>
</div>

<?php
// Incluindo o footer
include '../components/footer.php';
?>
</body>
</html> 