<?php
session_start();
require_once '../Controllers/User.php';
require_once '../config/config.php';


$user = new User(getPDO());

// Ensure user is logged in and is admin
if (!$user->isLoggedIn() || !$user->isAdmin()) {
    $_SESSION['error'] = 'You do not have permission to perform this action';
    header('Location: login.php');
    exit();
}

require_once dirname(__FILE__) . '/../Controllers/ArticleController.php';
$articleController = new ArticleController(getPDO());

// Get article ID from URL
$articleId = $_GET['id'];

// Get article data
try {
    $article = $articleController->get($articleId);
    if (!$article) {
        header('Location: articles.php?error=Article not found');
        exit;
    }
} catch (Exception $e) {
    header('Location: articles.php?error=' . urlencode($e->getMessage()));
    exit;
}

require_once 'includes/header.php';
?>

<div class="content-header">
    <h2><?php echo htmlspecialchars($article['title']); ?></h2>
    <div class="actions">
        <a href="articles.php" class="btn btn-secondary">Back to Articles</a>
    </div>
</div>

<div class="article-view">
    <div class="article-meta">
        <i class="fas fa-user"></i> <?php echo htmlspecialchars($article['author']); ?>
        <span style="margin: 0 10px;">•</span>
        <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
        <span style="margin: 0 10px;">•</span>
        <i class="fas fa-folder"></i> <?php echo htmlspecialchars($article['Category']); ?>
    </div>

    <div class="article-content">
        <?php 
        // Decode HTML entities and maintain formatting
        $content = $article['content'];
        // Replace &lt; with < and &gt; with >
        $content = str_replace(['&lt;', '&gt;'], ['<', '>'], $content);
        // Replace &amp; with &
        $content = str_replace('&amp;', '&', $content);
        // Replace &nbsp; with actual space
        $content = str_replace('&nbsp;', ' ', $content);
        // Decode other HTML entities
        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
        
        echo $content;
        ?>
    </div>

    <div class="article-actions">
        <a href="edit-article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Edit</a>
        <a href="delete-article.php?id=<?php echo $article['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
    </div>
</div>

<style>
.article-view {
    background: white;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 800px;
    margin: 20px auto;
}

.article-meta {
    color: #666;
    font-size: 0.9em;
    margin-bottom: 20px;
}

.article-meta i {
    margin-right: 5px;
}

.article-content {
    color: #444;
    line-height: 1.8;
    margin-bottom: 30px;
    word-wrap: break-word;
}

.article-content img {
    max-width: 100%;
    height: auto;
    margin: 15px 0;
}

.article-content b {
    font-weight: bold;
}

.article-content i {
    font-style: italic;
}

.article-content br {
    display: block;
    margin: 15px 0;
}

.article-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-primary {
    background-color: #007bff;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-secondary {
    background-color: #6c757d;
}

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
</style>

<?php
// Close PHP tag at end of file
?>
