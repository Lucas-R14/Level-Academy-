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
            max-width: 1200px;
            margin: 0 auto;
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
        @media (max-width: 900px) {
            .insideout-hero-content {
                flex-direction: column;
                gap: 2.5rem;
            }
            .insideout-hero-img {
                width: 260px;
                height: 260px;
                min-width: 180px;
            }
            .insideout-hero-text {
                max-width: 100%;
            }
            .insideout-title {
                font-size: 1.5rem;
                margin-bottom: 2rem;
            }
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

<?php
// Incluindo o footer
include '../components/footer.php';
?>
</body>
</html> 