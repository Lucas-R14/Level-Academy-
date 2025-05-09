<?php
session_start();
require_once '../config/config.php';
require_once dirname(__FILE__) . '/../Controllers/PodcastController.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Initialize PodcastController
$podcastController = new PodcastController(getPDO());

// Get podcast ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: podcasts.php?error=Invalid podcast ID');
    exit;
}

try {
    // Delete podcast
    $podcastController->delete($id);
    
    // Redirect to podcasts page with success message
    header('Location: podcasts.php?success=Podcast deleted successfully!');
    exit;
} catch (Exception $e) {
    header('Location: podcasts.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
