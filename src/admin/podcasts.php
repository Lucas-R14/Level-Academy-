<?php
session_start();
require_once '../config/config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
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
                        <td>
                            <a href="edit-podcast.php?id=<?php echo $podcast['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $podcast['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this podcast?')">Delete</button>
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

.btn-sm {
    padding: 4px 8px;
    font-size: 14px;
}

.actions {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
</style>