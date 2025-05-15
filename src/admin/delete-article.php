<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Controllers/User.php';
require_once __DIR__ . '/../Controllers/ArticleController.php';

// Initialize User
$user = new User($pdo);

// Ensure user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    header('Location: login.php');
    exit();
}

// Initialize ArticleController
$articleController = new ArticleController(getPDO());

// Get article ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

try {
    // Delete article
    $articleController->delete($id);
    
    // Redirect to dashboard with success message
    header('Location: index.php?success=Article deleted successfully!');
    exit;
} catch (Exception $e) {
    // Redirect back with error message
    header('Location: index.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
