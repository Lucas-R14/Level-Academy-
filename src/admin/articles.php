<?php
session_start();
require_once '../config/config.php';
require_once '../Controllers/User.php';

$user = new User(getPDO());

// Ensure user is logged in and is admin
if (!$user->isLoggedIn()) {
    $_SESSION['error'] = 'You do not have permission to perform this action';
    header('Location: login.php');
    exit();
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

require_once 'includes/header.php';
?>

<div class="content-header">
    <h2>Articles Management</h2>
    <div class="actions">
        <?php if ($user->isAdmin()): ?>
        <a href="add-article.php" class="btn btn-primary">Create New Article</a>
        <?php endif; ?>
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
                <a href="view-article.php?id=<?php echo $article['id']; ?>" class="btn btn-view" title="View Article">
                    <i class="fas fa-eye"></i> View
                </a>
                <?php if ($user->isAdmin()): ?>
                <a href="edit-article.php?id=<?php echo $article['id']; ?>" class="btn btn-primary" title="Edit Article">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="delete-article.php?id=<?php echo $article['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this article?')" title="Delete Article">
                    <i class="fas fa-trash"></i> Delete
                </a>
                <?php endif; ?>
            </div>
            <style>
                .btn-view {
                    background-color: #17a2b8;
                    color: white;
                    border: 1px solid #17a2b8;
                    padding: 6px 12px;
                    border-radius: 4px;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    gap: 5px;
                    transition: all 0.3s ease;
                }
                .btn-view:hover {
                    background-color: #138496;
                    border-color: #117a8b;
                    transform: translateY(-1px);
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }
                .btn-view i {
                    font-size: 14px;
                }
                .article-actions {
                    display: flex;
                    gap: 8px;
                    margin-top: 12px;
                }
            </style>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
