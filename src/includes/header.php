<?php
session_start();

// Get the current directory
$currentDir = __DIR__;
// Go up one level to the project root
$rootDir = dirname($currentDir);

// Include database configuration
require_once $rootDir . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Level Academy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="./index.php">Level Academy</a>
            </div>
            <ul class="nav-links">
                <li><a href="./index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                <li><a href="./articles.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'articles.php' ? 'active' : ''; ?>">Articles</a></li>
                <li><a href="./tournaments.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tournaments.php' ? 'active' : ''; ?>">Tournaments</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="./profile.php">Profile</a></li>
                    <li><a href="./includes/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="./login.php">Login</a></li>
                    <li><a href="./register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
