<?php
session_start();
require_once '../config/config.php';



// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once dirname(__FILE__) . '/../Controllers/ArticleController.php';
require_once dirname(__FILE__) . '/../Controllers/CategoryController.php';

// Initialize controllers
$articleController = new ArticleController(getPDO());
$categoryController = new CategoryController(getPDO());

// Get categories for dropdown
$categories = $categoryController->getAll();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {    
    try {
        // Validate and sanitize input
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);

        showAlert("$category");
        
        // Check if all required fields are filled
        if (!$title || !$author || !$category || !$content) {
            throw new Exception('Please fill in all required fields');
        }

        // Create article
        $articleController->create(
            $title,
            $content,
            $author,
            $category
        );

        // Redirect to articles page with success message
        header('Location: articles.php?success=Article created successfully!');
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}


function showAlert($message) {
    echo "<script>alert('" . htmlspecialchars($message, ENT_QUOTES) . "');</script>";
}

// Include header after form handling
ob_start();
require_once 'includes/header.php';
?>

<div class="content-header">
    <h2>Add New Article</h2>
    <div class="actions">
        <a href="articles.php" class="btn btn-secondary">Back to Articles</a>
    </div>
</div>

<?php if (isset($error)): ?>
    <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required class="form-control" placeholder="Enter article title">
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" required class="form-control" placeholder="Enter author name">
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required class="form-control">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category['id']); ?>"
                        <?php echo $category['id'] == 1 ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" required class="form-control" rows="10" placeholder="Enter article content"></textarea>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">Create Article</button>
            <a href="articles.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
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

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.form-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

/* Add some spacing between form elements */
.form-group label {
    display: block;
    margin-bottom: 5px;
}
</style>

<?php
// Close PHP tag at the end of the file
?>
