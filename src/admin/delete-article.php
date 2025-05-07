<?php
session_start();
require_once '../config/database.php';
require_once '../includes/Article.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Initialize Article class
$article = new Article($pdo);

// Get article ID from URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

try {
    // Delete article
    $article->delete($id);
    
    // Redirect to dashboard with success message
    header('Location: dashboard.php?success=Article deleted successfully!');
    exit;
} catch (Exception $e) {
    // Redirect back with error message
    header('Location: dashboard.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
