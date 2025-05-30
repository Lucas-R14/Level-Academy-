<?php
// Including the header
include '../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Creation - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/css/tournamentslist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="tournaments-hero">
        <h1 class="tournaments-title">Game Creation</h1>
        <p class="tournaments-subtitle">Our course combines traditional tabletop gaming with digital game design using tools like Unity and Roblox. Learn how to create and run tabletop role-playing games and apply those skills to video game development.</p>
    </div>

    <div class="tournaments-main-wrapper">
        <section class="tournaments-section">
            <div class="tournament-item">
                <div class="tournament-content-wrap">


                    <div class="camp-section">
                        <h2>Course Overview</h2>
                        <p>From character and world building to level design, we'll guide you through the creative process of tabletop RPGs and show you how to translate those skills to the digital realm of game design. By the end of the course, you'll have the skills to create your own games using Unity and Roblox.</p>
                    </div>

                    <div class="camp-sections-grid">
                        <div class="camp-box camp-section accent-esports">
                            <div class="accent-bar"></div>
                            <h2>Course Highlights</h2>
                            <div class="camp-features">
                                <div class="feature"><i class="fas fa-gamepad"></i><h3>Game Design</h3><p>Learn game mechanics and design principles</p></div>
                                <div class="feature"><i class="fas fa-users"></i><h3>Character Creation</h3><p>Design and develop game characters</p></div>
                                <div class="feature"><i class="fas fa-map"></i><h3>World Building</h3><p>Create immersive game worlds</p></div>
                                <div class="feature"><i class="fas fa-code"></i><h3>Unity Development</h3><p>Build games with Unity engine</p></div>
                                <div class="feature"><i class="fas fa-cube"></i><h3>Roblox Studio</h3><p>Create games in Roblox</p></div>
                                <div class="feature"><i class="fas fa-book"></i><h3>Story Writing</h3><p>Develop engaging narratives</p></div>
                                <div class="feature"><i class="fas fa-dice"></i><h3>Tabletop RPGs</h3><p>Learn tabletop game mechanics</p></div>
                                <div class="feature"><i class="fas fa-tasks"></i><h3>Level Design</h3><p>Create engaging game levels</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>Course Structure</h2>
                        <p>Participants will attend a series of 8 x 2-hour sessions that will focus on the following areas:</p>
                        <ul>
                            <li>Genres</li>
                            <li>Character Creation</li>
                            <li>World Building</li>
                            <li>Level Design</li>
                            <li>Task Design</li>
                            <li>Story writing and telling</li>
                            <li>Campaign Design</li>
                            <li>Hands-on TTRPG Sessions</li>
                            <li>Play testing</li>
                        </ul>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>Learning Outcomes</h2>
                        <p>By the end of the course, students will:</p>
                        <ul>
                            <li>Understand game genres and design principles</li>
                            <li>Create and develop game characters</li>
                            <li>Build immersive game worlds</li>
                            <li>Design engaging game levels</li>
                            <li>Write compelling game narratives</li>
                            <li>Master tabletop RPG mechanics</li>
                            <li>Create games using Unity</li>
                            <li>Develop games in Roblox Studio</li>
                            <li>Lead tabletop gaming sessions</li>
                            <li>Test and refine game designs</li>
                        </ul>
                    </div>
                    <div class="camp-box camp-section email-section">
                        <div class="email-container">
                            <div class="email-icon">
                                <i class="fas fa-gamepad"></i>
                            </div>
                            <div class="email-content">
                                <h2>Start Your Game Creation Journey</h2>
                                <p>Have questions about our game creation course? Get in touch with us today!</p>
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