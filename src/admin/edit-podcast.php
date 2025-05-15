<?php
session_start();
require_once '../config/config.php';
require_once '../Controllers/User.php';
require_once dirname(__FILE__) . '/../Controllers/PodcastController.php';


$user = new User(getPDO());

// Ensure user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    $_SESSION['error'] = 'You do not have permission to perform this action';
    header('Location: login.php');
    exit();
}


// Initialize PodcastController
$podcastController = new PodcastController(getPDO());

// Get podcast ID from URL
$podcastId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$podcastId) {
    header('Location: podcasts.php?error=Invalid podcast ID');
    exit;
}

// Get podcast data
try {
    $podcast = $podcastController->get($podcastId);
    if (!$podcast) {
        header('Location: podcasts.php?error=Podcast not found');
        exit;
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    try {
        // Validate and sanitize input
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $youtube_link = filter_input(INPUT_POST, 'youtube_link', FILTER_SANITIZE_URL);

        // Check if all required fields are filled
        if (!$title || !$youtube_link) {
            throw new Exception('Please fill in all required fields');
        }

        // Update podcast
        $podcastController->update($podcastId, [
            'title' => $title,
            'youtube_link' => $youtube_link
        ]);

        // Redirect to podcasts page with success message
        header('Location: podcasts.php?success=Podcast updated successfully!');
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Include header after form handling
ob_start();
require_once 'includes/header.php';
?>

<div class="content-header">
    <h2>Edit Podcast</h2>
    <div class="actions">
        <a href="podcasts.php" class="btn btn-secondary">Back to Podcasts</a>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required class="form-control" value="<?php echo htmlspecialchars($podcast['title']); ?>">
        </div>
        
        <div class="form-group">
            <label for="youtube_link">YouTube Link</label>
            <input type="url" id="youtube_link" name="youtube_link" required class="form-control" value="<?php echo htmlspecialchars($podcast['youtube_link']); ?>">
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Update Podcast</button>
            <a href="podcasts.php" class="btn btn-secondary">Cancel</a>
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

/* Add some spacing between form elements */
.form-group label {
    display: block;
    margin-bottom: 5px;
}
</style>

<?php
// Close PHP tag at the end of the file
?>
