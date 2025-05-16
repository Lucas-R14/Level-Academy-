<?php
// Include the database configuration
require_once '../config/config.php';
// Get the database connection
$pdo = getPDO();
require_once '../Controllers/ArticleController.php';
require_once '../Controllers/CategoryController.php';

// Get the article ID from the URL
$article_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$articleController = new ArticleController($pdo);
$categoryController = new CategoryController($pdo);

// Get the single article using the ID
$article = $articleController->getId($article_id);
$categories = $categoryController->getAll();

// Function to format date
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('F j, Y');
}

// Including the header
include '../components/header.php';

// If article not found, redirect to articles list
if (!$article) {
    echo '<script>window.location.href = "articleslist.php";</script>';
    exit;
}

// Get category name
$categoryId = $article['Category'];
$categoryName = "Uncategorized";
foreach ($categories as $cat) {
    if ($cat['id'] == $categoryId) {
        $categoryName = $cat['name'];
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/sidebar.css">
    <link rel="stylesheet" href="../../public/assets/css/article.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="article-hero">
        <h1 class="article-title-main"><?php echo htmlspecialchars($article['title']); ?></h1>
        <div class="article-meta">
            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($article['author']); ?></span>
            <span><i class="fas fa-calendar"></i> <?php echo formatDate($article['created_at']); ?></span>
            <span><i class="fas fa-folder"></i> <?php echo htmlspecialchars($categoryName); ?></span>
        </div>
    </div>

    <div class="article-main-wrapper">
        <section class="article-content-section">
            <div class="article-content">
                <?php 
                // Decode HTML entities and ensure proper line breaks
                $content = html_entity_decode($article['content'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                echo $content;
                ?>
            </div>
            
            <div class="article-share">
                <h3 class="article-share-title">Share This Article</h3>
                <div class="article-share-buttons">
                    <a href="https://facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($article['title']); ?>" target="_blank" class="share-btn"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-btn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <a href="articleslist.php" class="back-to-articles"><i class="fas fa-arrow-left"></i> Back to Articles</a>
        </section>
        
        <?php include '../components/sidebar.php'; ?>
    </div>

    <script src="../../public/assets/script.js"></script>

<?php
// Including the footer
include '../components/footer.php';
?>
</body>
</html>
