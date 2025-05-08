<?php
session_start();
require_once '../config/config.php';
require_once 'includes/header.php';
require_once dirname(__FILE__) . '/../Controllers/TournamentController.php';

// Initialize TournamentController
$tournamentController = new TournamentController(getPDO());

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['title'], $_POST['format'], $_POST['event_date'], $_POST['location'], $_POST['prize'], $_POST['entry_fee'], $_POST['registration_link'])) {
            $tournamentController->create([
                'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
                'format' => filter_input(INPUT_POST, 'format', FILTER_SANITIZE_STRING),
                'event_date' => filter_input(INPUT_POST, 'event_date', FILTER_SANITIZE_STRING),
                'location' => filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING),
                'prize' => filter_input(INPUT_POST, 'prize', FILTER_SANITIZE_STRING),
                'entry_fee' => filter_input(INPUT_POST, 'entry_fee', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
                'registration_link' => filter_input(INPUT_POST, 'registration_link', FILTER_SANITIZE_URL)
            ]);
            header('Location: tournaments.php?success=Tournament created successfully!');
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="content-header">
    <h2>Add New Tournament</h2>
    <div class="actions">
        <a href="tournaments.php" class="btn btn-secondary">Back to Tournaments</a>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required class="form-control">
        </div>
        <div class="form-group">
            <label for="format">Format</label>
            <input type="text" id="format" name="format" required class="form-control">
        </div>
        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" required class="form-control">
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required class="form-control">
        </div>
        <div class="form-group">
            <label for="prize">Prize</label>
            <input type="text" id="prize" name="prize" required class="form-control">
        </div>
        <div class="form-group">
            <label for="entry_fee">Entry Fee</label>
            <input type="number" id="entry_fee" name="entry_fee" step="0.01" required class="form-control">
        </div>
        <div class="form-group">
            <label for="registration_link">Registration Link</label>
            <input type="url" id="registration_link" name="registration_link" required class="form-control">
        </div>
        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Create Tournament</button>
            <a href="tournaments.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}
</style>

<?php
// Close PHP tag at the end of the file
?>
