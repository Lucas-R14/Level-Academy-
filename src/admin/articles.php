<?php
// Session and security check
session_start();
require_once '../config/config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Initialize ArticleController
require_once dirname(__FILE__) . '/../Controllers/ArticleController.php';
$articleController = new ArticleController(getPDO());

// Handle article creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize inputs
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);

        // Create new article
        $articleController->create($title, $content, $author, $category);
        redirect('articles.php?success=Article created successfully!');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all articles
$articles = $articleController->getAll();

// Get categories for dropdown
$categories = $articleController->getCategories();

// Include header after all PHP processing
require_once 'includes/header.php';
?>

<div class="content-header">
    <h2>Articles Management</h2>
    <div class="actions">
        <a href="add-article.php" class="btn btn-primary">Create New Article</a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
<?php endif; ?>

<div class="articles-grid">
    <?php if (empty($articles)): ?>
        <p>No articles found.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
        <div class="article-card">
            <h3><?php echo htmlspecialchars($article['title']); ?></h3>
            <div class="article-meta">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($article['author']); ?>
                <span style="margin: 0 10px;">•</span>
                <i class="fas fa-calendar"></i> <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
            </div>
            <div class="article-actions">
                <a href="edit-article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary">Edit</a>
                <a href="delete-article.php?id=<?php echo $article['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
