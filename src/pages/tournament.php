<?php
// Include the database configuration
require_once '../config/config.php';
// Get the database connection
$pdo = getPDO();
require_once '../Controllers/TournamentController.php';

// Get the tournament ID from the URL
$tournament_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$tournamentController = new TournamentController($pdo);

// Get the single tournament using the ID
$tournament = $tournamentController->get($tournament_id);

// Function to format date 
function formatDate($dateString, $format) {
    $date = new DateTime($dateString);
    return $date->format($format);
}

// Function to format time
function formatTime($timeString) {
    if (empty($timeString)) {
        return 'TBA';
    }
    $time = new DateTime($timeString);
    return $time->format('g:i A'); // Formats as 7:00 PM
}

// Function to get month abbreviation
function getMonthAbbr($dateString) {
    $date = new DateTime($dateString);
    return $date->format('M');
}

// Function to get day
function getDay($dateString) {
    $date = new DateTime($dateString);
    return $date->format('d');
}

// Including the header
include '../components/header.php';

// If tournament not found, redirect to tournaments list
if (!$tournament) {
    echo '<script>window.location.href = "tournaments.php";</script>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tournament['title']); ?> - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/css/tournaments.css">
    <link rel="stylesheet" href="../../public/assets/css/tournament.css">
    <link rel="stylesheet" href="../../public/assets/css/lightbox.css">
    <link rel="stylesheet" href="../../public/assets/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="tournament-hero">
        <h1 class="tournament-title-main"><?php echo htmlspecialchars($tournament['title']); ?></h1>
        <div class="tournament-meta">
            <span><i class="fas fa-gamepad"></i> <?php echo htmlspecialchars($tournament['Format']); ?></span>
            <span><i class="fas fa-calendar"></i> <?php echo formatDate($tournament['event_date'], 'F j, Y'); ?></span>
            <span><i class="fas fa-clock"></i> <?php echo formatTime($tournament['start_time']); ?></span>
            <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tournament['location']); ?></span>
            <span><i class="fas fa-ticket-alt"></i> Entry Fee: €<?php echo htmlspecialchars($tournament['entry_fee']); ?></span>
            <?php if (isset($tournament['prize']) && $tournament['prize']): ?>
                <span><i class="fas fa-trophy"></i> Prize Pool Available</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="tournaments-main-wrapper">
        <section class="tournament-content-section">
            <!-- Tournament Details -->
            <div class="tournament-content">
                <!-- Tournament Information Block -->
                <div class="tournament-info-block">
                    <h3 class="tournament-info-title">Tournament Details</h3>
                    
                    <div class="tournament-details-flex">
                        <!-- Tournament Featured Image (Left Side) -->
                        <?php if (!empty($tournament['image_path'])): ?>
                            <div class="tournament-featured-image">
                                <img src="../../public/<?php echo htmlspecialchars($tournament['image_path']); ?>" alt="<?php echo htmlspecialchars($tournament['title']); ?>">
                            </div>
                        <?php endif; ?>
                        
                        <ul class="tournament-info-list">
                            <li class="tournament-info-item">
                                <i class="fas fa-calendar-day"></i> 
                                <strong>Date:</strong> <?php echo formatDate($tournament['event_date'], 'F j, Y'); ?>
                            </li>
                            <li class="tournament-info-item">
                                <i class="fas fa-clock"></i> 
                                <strong>Time:</strong> <?php echo formatTime($tournament['start_time']); ?>
                            </li>
                            <li class="tournament-info-item">
                                <i class="fas fa-map-marker-alt"></i> 
                                <strong>Location:</strong> <?php echo htmlspecialchars($tournament['location']); ?>
                            </li>
                            <li class="tournament-info-item">
                                <i class="fas fa-gamepad"></i> 
                                <strong>Format:</strong> <?php echo htmlspecialchars($tournament['Format']); ?>
                            </li>
                            <li class="tournament-info-item">
                                <i class="fas fa-ticket-alt"></i> 
                                <strong>Entry Fee:</strong> €<?php echo htmlspecialchars($tournament['entry_fee']); ?>
                            </li>
                            <?php if (isset($tournament['prize']) && $tournament['prize']): ?>
                                <li class="tournament-info-item">
                                    <i class="fas fa-trophy"></i> 
                                    <strong>Prizes:</strong> Available
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <!-- Tournament Description -->
                <div class="tournament-details">
                    <h3>About This Tournament</h3>
                    <p>Join us for an exciting competition at Level Academy! This tournament features <?php echo htmlspecialchars($tournament['Format']); ?> gameplay in a competitive environment.</p>
                    
                    <p>Players of all skill levels are welcome to participate. Registration is required to secure your spot.</p>
                    
                    <h3>What to Expect</h3>
                    <ul>
                        <li>Professional tournament environment</li>
                        <li>Competitive matches</li>
                        <li>Networking opportunities with fellow gamers</li>
                        <?php if (isset($tournament['prize']) && $tournament['prize']): ?>
                            <li>Prizes for top performers</li>
                        <?php endif; ?>
                    </ul>
                    
                    <p>Don't miss this opportunity to showcase your skills and connect with the gaming community!</p>
                </div>

                <!-- Registration Button -->
                <div class="registration-section">
                    <a href="<?php echo htmlspecialchars($tournament['registration_link']); ?>" target="_blank" class="registration-btn">REGISTER NOW</a>
                </div>
            </div>
            
            <!-- Share Tournament Section -->
            <div class="share-tournament">
                <h3 class="share-tournament-title">Share This Tournament</h3>
                <div class="share-buttons">
                    <a href="https://facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($tournament['title']); ?>" target="_blank" class="share-btn"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <a href="tournamentslist.php" class="back-to-tournaments"><i class="fas fa-arrow-left"></i> Back to Tournaments</a>
        </section>
        
        <?php include '../components/sidebar.php'; ?>
    </div>

    <script src="../../public/assets/script.js"></script>
    <script src="../../public/assets/js/lightbox.js"></script>

<?php
// Including the footer
include '../components/footer.php';
?>
</body>
</html> 