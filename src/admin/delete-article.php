<?php
session_start();
require_once '../config/database.php';
require_once '../Controllers/ArticleController.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Initialize ArticleController
$articleController = new ArticleController(getPDO());

// Get article ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

try {
    // Delete article
    $articleController->delete($id);
    
    // Redirect to dashboard with success message
    header('Location: dashboard.php?success=Article deleted successfully!');
    exit;
} catch (Exception $e) {
    // Redirect back with error message
    header('Location: dashboard.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
