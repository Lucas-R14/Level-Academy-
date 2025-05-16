<?php
session_start();
require_once '../config/config.php';
require_once '../Controllers/User.php';

$user = new User(getPDO());

// Ensure user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    $_SESSION['error'] = 'You do not have permission to perform this action';
    header('Location: login.php');
    exit();
}

require_once '../Controllers/TournamentController.php';

// Initialize TournamentController
$tournamentController = new TournamentController(getPDO());

// Get all tournaments
$tournaments = $tournamentController->getAll();


require_once 'includes/header.php';
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
                <th>Image</th>
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
                    <td>
                        <?php if (!empty($tournament['image_path'])): ?>
                            <img src="/Level-Academy-/public/<?php echo htmlspecialchars($tournament['image_path']); ?>" alt="<?php echo htmlspecialchars($tournament['title']); ?>" class="tournament-image" style="width: 50px; height: 50px; object-fit: cover;">
                        <?php else: ?>
                            <span class="no-image">No Image</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($tournament['title']); ?></td>
                    <td><?php echo htmlspecialchars($tournament['Format']); ?></td>
                    <td><?php echo htmlspecialchars($tournament['event_date']); ?></td>
                    <td><?php echo htmlspecialchars($tournament['location']); ?></td>
                    <td><?php echo $tournament['prize'] == 1 ? 'Available' : 'Unavailable'; ?></td>
                    <td><?php echo htmlspecialchars($tournament['entry_fee']); ?></td>
                    <td class="actions-cell">
                        <a href="edit-tournament.php?id=<?php echo htmlspecialchars($tournament['id']); ?>" class="btn btn-edit" title="Edit Tournament">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="delete-tournament.php" method="POST" class="delete-form" data-id="<?php echo htmlspecialchars($tournament['id']); ?>">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($tournament['id']); ?>">
                            <button type="submit" class="btn btn-delete" title="Delete Tournament">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete form submission
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this tournament?')) {
                // Submit the form
                form.submit();
            }
        });
    });
});
</script>

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

.actions-cell {
    white-space: nowrap;
}

.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    margin-right: 5px;
}

.btn i {
    font-size: 14px;
}

.btn-edit {
    background-color: #17a2b8;
    color: white;
    border: 1px solid #17a2b8;
}

.btn-edit:hover {
    background-color: #138496;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.btn-delete {
    background-color: #dc3545;
    color: white;
    border: 1px solid #dc3545;
}

.btn-delete:hover {
    background-color: #c82333;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 0.875rem;
}
</style>

