<?php
require_once 'includes/header.php';
require_once dirname(__FILE__) . '/../includes/Tournament.php';

// Initialize Tournament class
$tournament = new Tournament($pdo);

// Handle tournament creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize inputs
        $data = [
            'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
            'event_date' => filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING),
            'location' => filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING),
            'prize' => filter_input(INPUT_POST, 'prize', FILTER_SANITIZE_STRING),
            'entry_fee' => filter_input(INPUT_POST, 'entry_fee', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
            'registration_link' => filter_input(INPUT_POST, 'registration_link', FILTER_SANITIZE_URL)
        ];

        // Create new tournament
        $tournament->create($data);
        header('Location: tournaments.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all tournaments
$tournaments = $tournament->getAll();
?>

<div class="content-header">
    <h2>Tournaments Management</h2>
    <div class="actions">
        <button class="btn btn-primary" onclick="document.getElementById('tournamentForm').style.display = 'block'">Create New Tournament</button>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo $error; ?></div>
<?php endif; ?>

<div class="articles-grid">
    <?php if (empty($tournaments)): ?>
        <p>No tournaments found.</p>
    <?php else: ?>
        <?php foreach ($tournaments as $tournament): ?>
        <div class="article-card">
            <h3><?php echo htmlspecialchars($tournament['title']); ?></h3>
            <div class="article-meta">
                <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($tournament['event_date'])); ?>
                <span style="margin: 0 10px;">•</span>
                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($tournament['location']); ?>
            </div>
            <div class="article-details">
                <p><i class="fas fa-trophy"></i> Prize: <?php echo htmlspecialchars($tournament['prize']); ?></p>
                <p><i class="fas fa-money-bill"></i> Entry Fee: $<?php echo number_format($tournament['entry_fee'], 2); ?></p>
            </div>
            <div class="article-actions">
                <a href="edit-tournament.php?id=<?php echo $tournament['id']; ?>" class="btn btn-primary">Edit</a>
                <a href="delete-tournament.php?id=<?php echo $tournament['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this tournament?')">Delete</a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Tournament Form Modal -->
<div id="tournamentForm" style="display: none;">
    <div class="card">
        <h2>Create New Tournament</h2>
        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="event_date">Event Date</label>
                <input type="date" id="event_date" name="event_date" required>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" required>
            </div>

            <div class="form-group">
                <label for="prize">Prize</label>
                <input type="text" id="prize" name="prize" required>
            </div>

            <div class="form-group">
                <label for="entry_fee">Entry Fee ($)</label>
                <input type="number" id="entry_fee" name="entry_fee" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="registration_link">Registration Link</label>
                <input type="url" id="registration_link" name="registration_link" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Tournament</button>
            <button type="button" class="btn btn-danger" onclick="document.getElementById('tournamentForm').style.display = 'none'">Cancel</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
