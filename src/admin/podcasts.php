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

require_once dirname(__FILE__) . '/../Controllers/PodcastController.php';

// Initialize PodcastController
$podcastController = new PodcastController(getPDO());

// Handle delete form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
            $podcastController->delete($_POST['id']);
            header('Location: podcasts.php?success=Podcast deleted successfully!');
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all podcasts
$podcasts = $podcastController->getAll();

require_once 'includes/header.php';
?>

<div class="content-header">
    <h2>Podcasts Management</h2>
    <div class="actions">
        <a href="add-podcast.php" class="btn btn-primary">Add New Podcast</a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>YouTube Link</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($podcasts as $podcast): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($podcast['id']); ?></td>
                        <td><?php echo htmlspecialchars($podcast['title']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($podcast['youtube_link']); ?>" target="_blank"><?php echo htmlspecialchars($podcast['youtube_link']); ?></a></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($podcast['created_at'])); ?></td>
                        <td class="actions-cell">
                            <a href="edit-podcast.php?id=<?php echo $podcast['id']; ?>" class="btn btn-edit" title="Edit Podcast">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this podcast?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $podcast['id']; ?>">
                                <button type="submit" class="btn btn-delete" title="Delete Podcast">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.table th {
    background-color: #f8f9fa;
}

.table-responsive {
    overflow-x: auto;
    margin-bottom: 20px;
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

.actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
</style>