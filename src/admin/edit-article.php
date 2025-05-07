<?php
session_start();
require_once '../config/database.php';
require_once '../includes/Article.php';

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
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

// Get article data
$articleData = $article->get($id);
if (!$articleData) {
    header('Location: dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Sanitize inputs
        $data = [
            'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
            'content' => filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING),
            'author' => filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING)
        ];

        // Update article
        $article->update($id, $data);
        
        // Redirect to dashboard with success message
        header('Location: dashboard.php?success=Article updated successfully!');
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - Level Academy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background: #1a73e8;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn:hover {
            background: #1557b0;
        }
        .message {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        .success {
            background: #d4edda;
            color: #155746;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Article</h1>

        <?php if (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($articleData['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($articleData['author']); ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($articleData['content']); ?></textarea>
            </div>

            <button type="submit" class="btn">Update Article</button>
        </form>

        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
