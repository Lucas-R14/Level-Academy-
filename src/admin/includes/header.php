<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Level Academy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../public/assets/css/admin.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h1>Admin Panel</h1>
        </div>
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li>
                    <a href="index.php" class="<?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="articles.php" class="<?php echo $current_page === 'articles.php' ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper"></i> Articles
                    </a>
                </li>
                <li>
                    <a href="categories.php" class="<?php echo $current_page === 'categories.php' ? 'active' : ''; ?>">
                        <i class="fas fa-list"></i> Categories
                    </a>
                </li>
                <li>
                    <a href="tournaments.php" class="<?php echo $current_page === 'tournaments.php' ? 'active' : ''; ?>">
                        <i class="fas fa-trophy"></i> Tournaments
                    </a>
                </li>
                <li>
                    <a href="podcasts.php" class="<?php echo $current_page === 'podcasts.php' ? 'active' : ''; ?>">
                        <i class="fas fa-podcast"></i> Podcasts
                    </a>
                </li>
                <?php if ($user->isLoggedIn() && $user->isAdmin()): ?>
                <li>
                    <a href="users.php" class="<?php echo in_array($current_page, ['users.php', 'create-user.php', 'edit-user.php']) ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="main-content">
