<?php
// Including the header
include '../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birthday Parties - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/css/tournamentslist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="tournaments-hero">
        <h1 class="tournaments-title">Birthday Parties</h1>
        <p class="tournaments-subtitle">Create unforgettable memories with our unique gaming birthday party experiences!</p>
    </div>

    <div class="tournaments-main-wrapper">
        <section class="tournaments-section">
            <div class="tournament-item">
                <div class="tournament-content-wrap">


                    <div class="camp-section">
                        <h2>Party Overview</h2>
                        <p>From Fortnite to Minecraft or a full blown esports experience, we cater for all types of gamers. Create an unforgettable birthday celebration in our state-of-the-art gaming facility.</p>
                    </div>

                    <div class="camp-sections-grid">
                        <div class="camp-box camp-section accent-esports">
                            <div class="accent-bar"></div>
                            <h2>Party Features</h2>
                            <div class="camp-features">
                                <div class="feature"><i class="fas fa-gamepad"></i><h3>Gaming Experience</h3><p>State-of-the-art gaming setup</p></div>
                                <div class="feature"><i class="fas fa-birthday-cake"></i><h3>Custom Themes</h3><p>Choose your favorite game theme</p></div>
                                <div class="feature"><i class="fas fa-users"></i><h3>Group Gaming</h3><p>Up to 13 players</p></div>
                                <div class="feature"><i class="fas fa-utensils"></i><h3>Food Options</h3><p>Various catering packages</p></div>
                                <div class="feature"><i class="fas fa-couch"></i><h3>Parent Lounge</h3><p>Comfortable waiting area</p></div>
                                <div class="feature"><i class="fas fa-gift"></i><h3>Party Bags</h3><p>Custom themed goodies</p></div>
                                <div class="feature"><i class="fas fa-video"></i><h3>Live Streaming</h3><p>Optional streaming setup</p></div>
                                <div class="feature"><i class="fas fa-star"></i><h3>Special Extras</h3><p>Custom animations & more</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>Party Packages</h2>
                        <div class="program-details">
                            <div class="package">
                                <h3>Basic Package - €250</h3>
                                <p>Birthday Party with gaming setup. Bring your own food and cake.</p>
                            </div>
                            <div class="package">
                                <h3>Epic Package - €350</h3>
                                <p>Full party experience with catering and 3D cake included.</p>
                            </div>
                            <div class="package">
                                <h3>Legendary Package</h3>
                                <p>Ultimate birthday experience with all extras. Contact for pricing.</p>
                            </div>
                        </div>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>How It Works</h2>
                        <ol>
                            <li>Choose your gaming theme</li>
                            <li>Select your preferred package</li>
                            <li>Invite up to 13 players</li>
                            <li>Choose your food package</li>
                            <li>Enjoy our parent lounge</li>
                            <li>Add any special extras</li>
                        </ol>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>Contact Us</h2>
                        <p>Ready to plan your perfect gaming birthday party? Contact us at info@levelacademy.com.mt to discuss your requirements and make a booking.</p>
                    </div>
                    <div class="camp-box camp-section email-section">
                        <div class="email-container">
                            <div class="email-icon">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div class="email-content">
                                <h2>Ready to Plan Your Party?</h2>
                                <p>Contact us today to book your gaming birthday party experience!</p>
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