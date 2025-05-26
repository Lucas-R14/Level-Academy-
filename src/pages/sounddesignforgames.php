<?php
// Including the header
include '../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sound Design for Games - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/css/tournamentslist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="tournaments-hero">
        <h1 class="tournaments-title">Sound Design for Games</h1>
        <p class="tournaments-subtitle">This 10-lesson course is designed for students between the ages of 13 and 18 who want to learn how to create immersive soundscapes for video games. Students will learn how to use software and hardware tools to create sound effects, music, and dialogue that enhance the gaming experience.</p>
    </div>

    <div class="tournaments-main-wrapper">
        <section class="tournaments-section">
            <div class="tournament-item">
                <div class="tournament-content-wrap">

                    <div class="camp-section">
                        <h2>Course Overview</h2>
                        <p>This 10-lesson course is designed for students between the ages of 13 and 18 who want to learn how to create immersive soundscapes for video games. Students will learn how to use software and hardware tools to create sound effects, music, and dialogue that enhance the gaming experience. They'll also get to work on real-world game projects, giving them the opportunity to see their work come to life in a fun and interactive way.</p>
                    </div>

                    <div class="camp-sections-grid">
                        <div class="camp-box camp-section accent-esports">
                            <div class="accent-bar"></div>
                            <h2>Learning Outcomes</h2>
                            <div class="camp-features">
                                <div class="feature"><i class="fas fa-volume-up"></i><h3>Sound Design</h3><p>Define sound design in media production</p></div>
                                <div class="feature"><i class="fas fa-music"></i><h3>Music Production</h3><p>Understand music production applications</p></div>
                                <div class="feature"><i class="fas fa-lightbulb"></i><h3>Creative Thinking</h3><p>Analyze ideas from multiple perspectives</p></div>
                                <div class="feature"><i class="fas fa-sliders-h"></i><h3>Sound Creation</h3><p>Create unique sounds using design principles</p></div>
                                <div class="feature"><i class="fas fa-puzzle-piece"></i><h3>Arrangement</h3><p>Develop customized arrangements</p></div>
                                <div class="feature"><i class="fas fa-gamepad"></i><h3>Virtual Environment</h3><p>Apply sound design in games</p></div>
                                <div class="feature"><i class="fas fa-cogs"></i><h3>Integration</h3><p>Integrate elements for coherent worlds</p></div>
                                <div class="feature"><i class="fas fa-users"></i><h3>Audience Focus</h3><p>Evaluate content for specific audiences</p></div>
                            </div>
                        </div>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>Course Structure</h2>
                        <p>The course consists of 10 sessions covering:</p>
                        <ul>
                            <li>Introduction to Audio and FX Units (Data storage and File Types)</li>
                            <li>Transposing, Fades, Automations, and Editing</li>
                            <li>Spatial Sounds (Utility, Reverbs, Widening, and Stereo Enhancements)</li>
                            <li>EQ and Ear Training</li>
                            <li>Layering & Processing</li>
                            <li>Conceptualizing Sounds</li>
                            <li>Mixing (Using Sends, Compression)</li>
                            <li>Looping & Arrangement</li>
                            <li>Exporting and Labelling</li>
                            <li>The Industry & Tools of the Trade (FMOD, Unity, Programming Basics)</li>
                        </ul>
                    </div>

                    <div class="camp-box camp-section">
                        <h2>Target Group</h2>
                        <p>This course is perfect for young aspiring sound designers who want to learn how to create immersive audio experiences for video games. No prior sound design experience is required - just bring your enthusiasm and creativity!</p>
                    </div>
                    <div class="camp-box camp-section email-section">
                        <div class="email-container">
                            <div class="email-icon">
                                <i class="fas fa-volume-up"></i>
                            </div>
                            <div class="email-content">
                                <h2>Create Immersive Soundscapes</h2>
                                <p>Questions about our sound design course? We'd love to hear from you!</p>
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
