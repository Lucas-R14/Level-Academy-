<?php
// Include the database configuration
require_once '../config/config.php';
// Get the database connection
$pdo = getPDO();
require_once '../Controllers/TournamentController.php';

$tournamentController = new TournamentController($pdo);
$tournaments = $tournamentController->getAll();

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

// Function to truncate text to a certain number of words
function truncateText($text, $limit = 30) {
    $text = strip_tags($text);
    $words = explode(' ', $text);
    if (count($words) > $limit) {
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }
    return $text;
}

// Including the header
include '../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tournaments - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/css/tournaments.css">
    <link rel="stylesheet" href="../../public/assets/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="tournaments-hero">
        <h1 class="tournaments-title">Tournaments</h1>
        <p class="tournaments-subtitle">Join our exciting esports competitions and showcase your skills in a professional setting. Level Academy hosts regular tournaments with amazing prizes and opportunities.</p>
    </div>

    <div class="tournaments-main-wrapper">
        <section class="tournaments-section">
            <?php if (empty($tournaments)): ?>
                <div class="tournament-item">
                    <div class="tournament-content-wrap">
                        <h2 class="tournament-title">No tournaments found</h2>
                        <div class="tournament-details">
                            <p>There are currently no tournaments scheduled. Check back soon for upcoming events!</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($tournaments as $tournament): ?>
                    <div class="tournament-item">
                        <div class="tournament-date-block">
                            <div class="tournament-date-day"><?php echo getDay($tournament['event_date']); ?></div>
                            <div class="tournament-date-month"><?php echo getMonthAbbr($tournament['event_date']); ?></div>
                        </div>
                        <div class="tournament-content-wrap">
                            <div class="tournament-meta">
                                <span><i class="fas fa-gamepad"></i> <?php echo htmlspecialchars($tournament['Format']); ?></span>
                                <span><i class="fas fa-calendar"></i> <?php echo formatDate($tournament['event_date'], 'F j, Y'); ?></span>
                                <span><i class="fas fa-clock"></i> <?php echo formatTime($tournament['start_time']); ?></span>
                            </div>
                            <a href="tournament.php?id=<?php echo $tournament['id']; ?>" class="tournament-title"><?php echo htmlspecialchars($tournament['title']); ?></a>
                            
                            <div class="tournament-info">
                                <div class="tournament-info-item">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tournament['location']); ?>
                                </div>
                                <div class="tournament-info-item">
                                    <span class="time-badge"><i class="fas fa-clock"></i> <?php echo formatTime($tournament['start_time']); ?></span>
                                </div>
                                <?php if (isset($tournament['prize']) && $tournament['prize']): ?>
                                    <div class="tournament-info-item">
                                        <span class="prize-badge"><i class="fas fa-trophy"></i> Prize Pool</span>
                                    </div>
                                <?php endif; ?>
                                <div class="tournament-info-item">
                                    <span class="fee-badge">€<?php echo htmlspecialchars($tournament['entry_fee']); ?></span>
                                </div>
                            </div>
                            
                            <a href="<?php echo htmlspecialchars($tournament['registration_link']); ?>" target="_blank" class="register-btn">REGISTER NOW</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
        
        <?php include '../components/sidebar.php'; ?>
    </div>

    <script src="../../public/assets/script.js"></script>

<?php
// Including the footer
include '../components/footer.php';
?>
</body>
</html>
