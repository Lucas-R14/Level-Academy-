<?php
// Including the header
include '../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photography for Kids - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/css/tournamentslist.css">
    <link rel="stylesheet" href="../../public/assets/css/email-section.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="tournaments-hero">
        <h1 class="tournaments-title">Photography for Kids</h1>
        <p class="tournaments-subtitle">Our Photography for Kids Course is designed to spark imagination, nurture creativity, and empower young minds to capture captivating moments!</p>
    </div>

    <div class="tournaments-main-wrapper">
        <section class="tournaments-section">
            <div class="tournament-item">
                <div class="tournament-content-wrap">


                    <div class="camp-section">
                        <h2>Course Overview</h2>
                        <p>Are you ready to embark on an exciting journey into the world of photography? Look no further! Our Photography Course for Kids is designed to spark imagination, nurture creativity, and empower young minds to capture captivating moments through the lens.</p>
                    </div>

                    <div class="camp-sections-grid">
                        <div class="camp-box camp-section accent-esports">
                            <div class="accent-bar"></div>
                            <h2>Course Highlights</h2>
                            <div class="camp-features">
                                <div class="feature"><i class="fas fa-camera"></i><h3>Magic of Photography</h3><p>From basics to advanced techniques</p></div>
                                <div class="feature"><i class="fas fa-hands"></i><h3>Hands-On Learning</h3><p>Practical activities with cameras</p></div>
                                <div class="feature"><i class="fas fa-palette"></i><h3>Creativity</h3><p>Think outside the box</p></div>
                                <div class="feature"><i class="fas fa-chalkboard-teacher"></i><h3>Expert Guidance</h3><p>Personalized attention</p></div>
                                <div class="feature"><i class="fas fa-gamepad"></i><h3>Interactive</h3><p>Fun activities and challenges</p></div>
                                <div class="feature"><i class="fas fa-laptop"></i><h3>Digital Editing</h3><p>Photo editing skills</p></div>
                                <div class="feature"><i class="fas fa-images"></i><h3>Showcase</h3><p>Exhibition of best shots</p></div>
                                <div class="feature"><i class="fas fa-heart"></i><h3>Lifelong Passion</h3><p>Build lasting interest</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>Course Structure</h2>
                        <p>The course consists of 8 sessions covering:</p>
                        <ul>
                            <li>Introduction to Photography</li>
                            <li>Understanding Light</li>
                            <li>Exploring Camera Settings</li>
                            <li>Capturing Motion and Freeze Frames</li>
                            <li>The Power of Perspective</li>
                            <li>Introduction to Photo Editing</li>
                            <li>Exploring Different Genres</li>
                            <li>Showcasing and Sharing Photos</li>
                        </ul>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>Target Group</h2>
                        <p>This course is perfect for young photography enthusiasts who want to learn the art of capturing moments and expressing themselves through images. No prior photography experience is required - just bring your enthusiasm and creativity!</p>
                    </div>
                    <div class="camp-box camp-section email-section">
                        <div class="email-container">
                            <div class="email-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="email-content">
                                <h2>Capture the Moment With Us</h2>
                                <p>Interested in our photography course? Reach out to learn more about our next session!</p>
                                <div class="email-button">
                                    <a href="mailto:spiterik@gmail.com" class="email-link">
                                        <i class="fas fa-paper-plane"></i> Email Us at spiterik@gmail.com
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .email-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px;
            margin-top: 30px;
            text-align: center;
        }
        
        .email-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .email-icon {
            font-size: 3rem;
            color: #6c5ce7;
            margin-bottom: 10px;
        }
        
        .email-content h2 {
            color: #2d3436;
            margin-bottom: 15px;
            font-size: 1.8rem;
        }
        
        .email-content p {
            color: #636e72;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }
        
        .email-button {
            margin-top: 15px;
        }
        
        .email-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: #6c5ce7;
            color: white !important;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
        }
        
        .email-link:hover {
            background: #5649c0;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 92, 231, 0.4);
        }
        
        .email-link i {
            font-size: 1.1em;
        }
        
        @media (max-width: 768px) {
            .email-section {
                padding: 20px 15px;
            }
            
            .email-content h2 {
                font-size: 1.5rem;
            }
            
            .email-link {
                padding: 10px 20px;
                font-size: 0.95rem;
            }
        }
    </style>

<?php
// Including the footer
include '../components/footer.php';
?>
</body>
</html> 