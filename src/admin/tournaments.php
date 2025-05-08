<?php
session_start();
require_once '../config/config.php';
require_once 'includes/header.php';
require_once dirname(__FILE__) . '/../Controllers/TournamentController.php';

// Initialize TournamentController
$tournamentController = new TournamentController(getPDO());

// Handle delete form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
            $tournamentController->delete($_POST['id']);
            header('Location: tournaments.php?success=Tournament deleted successfully!');
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all tournaments
$tournaments = $tournamentController->getAll();
?>

<div class="content-header">
    <h2>Tournaments Management</h2>
    <div class="actions">
        <a href="add-tournament.php" class="btn btn-primary">Create New Tournament</a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Format</th>
                <th>Event Date</th>
                <th>Location</th>
                <th>Prize</th>
                <th>Entry Fee</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tournaments)): ?>
                <tr>
                    <td colspan="7">No tournaments found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($tournaments as $tournament): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tournament['title']); ?></td>
                    <td><?php echo htmlspecialchars($tournament['Format']); ?></td>
                    <td><?php echo htmlspecialchars($tournament['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($tournament['location']); ?></td>
                    <td><?php echo htmlspecialchars($tournament['prize']); ?></td>
                    <td><?php echo htmlspecialchars($tournament['entry_fee']); ?></td>
                    <td>
                        <a href="edit-tournament.php?id=<?php echo htmlspecialchars($tournament['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this tournament?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($tournament['id']); ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
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

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    background-color: #f8f9fa;
}

.table tr:hover {
    background-color: #f5f5f5;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 0.875rem;
}
</style>

<?php
// Close PHP tag at the end of the file
?>
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

