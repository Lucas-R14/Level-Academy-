<?php
// Include the database configuration
require_once '../config/config.php';
// Get the database connection
$pdo = getPDO();
require_once '../Controllers/ArticleController.php';
require_once '../Controllers/CategoryController.php';

$articleController = new ArticleController($pdo);
$categoryController = new CategoryController($pdo);
$articles = $articleController->getAll();
$categories = $categoryController->getAll();

// Function to get month abbreviation
function getMonthAbbr($dateString) {
    $date = new DateTime($dateString);
    return $date->format('M');
}

// Function to get day
function getDay($dateString) {
    $date = new DateTime($dateString);
    return $date->format('d');
}

// Function to truncate text to a certain number of words
function truncateText($text, $limit = 30) {
    $text = strip_tags($text);
    $words = explode(' ', $text);
    if (count($words) > $limit) {
        return implode(' ', array_slice($words, 0, $limit)) . '...';
    }
    return $text;
}

// Including the header
include '../components/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - Level Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;800&family=Exo+2:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/assets/style.css">
    <link rel="stylesheet" href="../../public/assets/sidebar.css">
    <link rel="stylesheet" href="../../public/assets/css/articleslist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <div class="articles-hero">
        <h1 class="articles-title">Articles</h1>
    </div>

    <div class="articles-main-wrapper">
        <section class="articles-section">
            <?php if (empty($articles)): ?>
                <div class="article-item">
                    <div class="article-content-wrap">
                        <h2 class="article-title">No articles found</h2>
                        <div class="article-excerpt">
                            <p>There are currently no articles to display.</p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <div class="article-item">
                        <div class="article-date-block">
                            <div class="article-date-day"><?php echo getDay($article['created_at']); ?></div>
                            <div class="article-date-month"><?php echo getMonthAbbr($article['created_at']); ?></div>
                        </div>
                        <div class="article-content-wrap">
                            <div class="article-date-meta">
                                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($article['author']); ?></span>
                                <?php 
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
                                <span><i class="fas fa-folder"></i> <?php echo htmlspecialchars($categoryName); ?></span>
                            </div>
                            <a href="article.php?id=<?php echo $article['id']; ?>" class="article-title"><?php echo htmlspecialchars($article['title']); ?></a>
                            <div class="article-excerpt">
                                <?php echo truncateText($article['content']); ?>
                            </div>
                            <a href="article.php?id=<?php echo $article['id']; ?>" class="read-more">READ MORE</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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