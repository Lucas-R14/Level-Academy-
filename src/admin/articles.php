<?php
require_once 'includes/header.php';
require_once dirname(__FILE__) . '/../includes/Article.php';

// Initialize Article class
$article = new Article($pdo);

// Handle article creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize inputs
        $data = [
            'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'content' => filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING),
            'author' => filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING)
        ];

        // Handle file upload
        if (!empty($_FILES['featured_image']['name'])) {
            $uploadDir = '../assets/images/articles/';
            $fileName = uniqid() . '_' . $_FILES['featured_image']['name'];
            $uploadPath = $uploadDir . $fileName;

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $uploadPath)) {
                $data['featured_image'] = $fileName;
            }
        }

        // Create new article
        $article->create($data);
        header('Location: articles.php');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Get all articles
$articles = $article->getAll();
?>

<div class="content-header">
    <h2>Articles Management</h2>
    <div class="actions">
        <button class="btn btn-primary" onclick="document.getElementById('articleForm').style.display = 'block'">Create New Article</button>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo $error; ?></div>
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

<!-- Article Form Modal -->
<div id="articleForm" style="display: none;">
    <div class="card">
        <h2>Create New Article</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" required>
            </div>

            <div class="form-group">
                <label for="featured_image">Featured Image</label>
                <input type="file" id="featured_image" name="featured_image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Create Article</button>
            <button type="button" class="btn btn-danger" onclick="document.getElementById('articleForm').style.display = 'none'">Cancel</button>
        </form>
    </div>
</div>

<?php require_once dirname(__FILE__) . '/includes/footer.php'; ?>
