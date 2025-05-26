<?php
// Including the header
include '../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Creation - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/css/tournamentslist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="tournaments-hero">
        <h1 class="tournaments-title">Content Creation</h1>
        <p class="tournaments-subtitle">Content creation is a Digital Marketing strategy that all companies and brands need to pay attention to if they want to communicate relevantly to their target market and differentiate from competition.</p>
    </div>

    <div class="tournaments-main-wrapper">
        <section class="tournaments-section">
            <div class="tournament-item">
                <div class="tournament-content-wrap">
                    <div class="camp-section">
                        <h2>Program Overview</h2>
                        <p>We will cover the fundamentals of Video Production, Audio Production, Streaming and 3D Printing. Upon completing these workshops you will have a clearer idea of these career paths:</p>
                        <ul>
                            <li>Streamer</li>
                            <li>Video Editor</li>
                            <li>Sound Editor</li>
                            <li>3D Printing</li>
                            <li>Digital/Online Content Creator</li>
                            <li>Social Media Content Creator</li>
                            <li>Visual Content Creator</li>
                            <li>Branded Content Creator</li>
                            <li>Community creator</li>
                            <li>Influencer</li>
                            <li>Camera Operator</li>
                        </ul>
                    </div>

                    <div class="camp-sections-grid">
                        <div class="camp-box camp-section accent-esports">
                            <div class="accent-bar"></div>
                            <h2>Video Production</h2>
                            <div class="camp-features">
                                <div class="feature"><i class="fas fa-list"></i><h3>Three Processes</h3><p>Pre-Production, Production and Post-Production</p></div>
                                <div class="feature"><i class="fas fa-hdd-o"></i><h3>Hardware</h3><p>Right requirements for editing and recording</p></div>
                                <div class="feature"><i class="fas fa-window-maximize"></i><h3>Software</h3><p>Premiere Pro, Filmora, Davinci Resolve</p></div>
                                <div class="feature"><i class="fas fa-scissors"></i><h3>Editing</h3><p>Fundamental tips and tricks for video content</p></div>
                            </div>
                        </div>

                        <div class="camp-box camp-section accent-game">
                            <div class="accent-bar"></div>
                            <h2>Audio Production</h2>
                            <div class="camp-features">
                                <div class="feature"><i class="fas fa-microphone"></i><h3>Equipment</h3><p>Quality microphones and speakers/headphones</p></div>
                                <div class="feature"><i class="fas fa-file-audio-o"></i><h3>Sound Editing</h3><p>Manipulate and edit audio to suit your style</p></div>
                                <div class="feature"><i class="fas fa-volume-up"></i><h3>Learning Waves</h3><p>Understanding frequencies and decibels</p></div>
                                <div class="feature"><i class="fas fa-headphones"></i><h3>Creating Sounds</h3><p>Creating your own Foley and sound effects</p></div>
                            </div>
                        </div>

                        <div class="camp-box camp-section accent-esports">
                            <div class="accent-bar"></div>
                            <h2>Streaming</h2>
                            <div class="camp-features">
                                <div class="feature"><i class="fas fa-share-square"></i><h3>Platforms</h3><p>Twitch, YouTube, Facebook and more</p></div>
                                <div class="feature"><i class="fas fa-hdd-o"></i><h3>Hardware</h3><p>From mobile to full broadcasting studio</p></div>
                                <div class="feature"><i class="fas fa-window-maximize"></i><h3>Software</h3><p>Broadcasting software and tools</p></div>
                                <div class="feature"><i class="fas fa-stop-circle"></i><h3>GO LIVE!</h3><p>Hands-on session to start broadcasting</p></div>
                            </div>
                        </div>

                        <div class="camp-box camp-section accent-game">
                            <div class="accent-bar"></div>
                            <h2>3D Printing</h2>
                            <div class="camp-features">
                                <div class="feature"><i class="fas fa-download"></i><h3>Online Resources</h3><p>Download, edit and resize models</p></div>
                                <div class="feature"><i class="fas fa-plus-square"></i><h3>Creation</h3><p>Design and print custom models</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="camp-box camp-section email-section">
                        <div class="email-container">
                            <div class="email-icon">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div class="email-content">
                                <h2>Ready to Get Started?</h2>
                                <p>Contact us today to learn more about our Content Creation program!</p>
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