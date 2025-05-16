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
    <link rel="stylesheet" href="../../public/assets/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .tournaments-hero {
            background-image: url('../../public/assets/images/tournaments/banner.jpg');
            background-size: cover;
            background-position: center;
            padding: 8rem 0 6rem 0;
            position: relative;
        }
        
        .tournaments-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0,0,0,0.8), rgba(0,0,0,0.6));
            z-index: 1;
        }
        
        .tournaments-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 3.5rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 0;
            margin-bottom: 1rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
            position: relative;
            z-index: 2;
            animation: pulse 2s infinite alternate;
        }
        
        .tournaments-subtitle {
            font-family: 'Exo 2', sans-serif;
            font-size: 1.2rem;
            color: #fff;
            text-align: center;
            max-width: 700px;
            margin: 0 auto 2rem;
            position: relative;
            z-index: 2;
        }
        
        .tournaments-main-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 3rem;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            min-height: 80vh;
            padding: 0 15px;
        }
        
        .tournaments-section {
            flex: 1;
            max-width: 900px;
        }
        
        .tournament-item {
            display: flex;
            margin-bottom: 2rem;
            border-top: 1px solid rgba(175, 4, 232, 0.2);
            padding-top: 1.5rem;
        }
        
        .tournament-date-block {
            width: 120px;
            min-width: 120px;
            background: var(--primary);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
            height: 120px;
            margin-right: 1.5rem;
        }
        
        .tournament-date-day {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 0.3rem;
        }
        
        .tournament-date-month {
            font-family: 'Exo 2', sans-serif;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        
        .tournament-meta {
            display: flex;
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 0.4rem;
        }
        
        .tournament-meta span {
            display: flex;
            align-items: center;
            margin-right: 1rem;
        }
        
        .tournament-meta i {
            margin-right: 0.3rem;
            font-size: 0.8rem;
        }
        
        .tournament-content-wrap {
            flex: 1;
        }
        
        .tournament-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            margin-bottom: 0.7rem;
            color: #fff;
            text-decoration: none;
            transition: color 0.2s;
            display: block;
        }
        
        .tournament-title:hover {
            color: var(--primary);
        }
        
        .tournament-details {
            color: #aaa;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.2rem;
        }
        
        .tournament-info {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        
        .tournament-info-item {
            display: flex;
            align-items: center;
            margin-right: 1.5rem;
            margin-bottom: 0.5rem;
            color: #ddd;
        }
        
        .tournament-info-item i {
            margin-right: 0.5rem;
            color: var(--primary);
        }
        
        .register-btn {
            display: inline-block;
            background: var(--gradient);
            background-size: 200% 100%;
            color: #fff;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            font-family: 'Exo 2', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.3s;
            border-radius: 4px;
            box-shadow: 0 4px 15px rgba(175, 4, 232, 0.3);
            animation: gradient-shift 4s ease infinite;
        }
        
        .register-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(175, 4, 232, 0.5);
            background-position: 100% 50%;
        }
        
        .fee-badge {
            display: inline-block;
            background: var(--secondary);
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: 0.9rem;
            margin-right: 1rem;
        }
        
        .prize-badge {
            display: inline-block;
            background: #04E8D4;
            color: #000;
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .time-badge {
            display: inline-flex;
            align-items: center;
            background: var(--quaternary);
            color: #fff;
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: 0.9rem;
            margin-right: 1rem;
        }
        
        .time-badge i {
            margin-right: 0.4rem;
        }
        
        @media (max-width: 1100px) {
            .tournaments-title {
                font-size: 2.5rem;
            }
            
            .tournaments-main-wrapper {
                flex-direction: column;
                align-items: stretch;
                gap: 2rem;
            }
            
            .tournament-item {
                flex-direction: column;
            }
            
            .tournament-date-block {
                margin-bottom: 1rem;
                width: 100%;
                height: 80px;
                flex-direction: row;
            }
            
            .tournament-date-day {
                margin-right: 0.5rem;
                margin-bottom: 0;
            }
        }
    </style>
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
