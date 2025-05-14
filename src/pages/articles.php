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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .articles-hero {
            background: #121212;
            padding: 6rem 0 4rem 0;
        }
        .articles-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.7rem;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 0;
            margin-bottom: 3.5rem;
        }
        .articles-main-wrapper {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 3rem;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            min-height: 80vh;
            padding: 0 15px;
        }
        .articles-section {
            flex: 1;
            max-width: 900px;
        }
        .article-item {
            display: flex;
            margin-bottom: 2rem;
            border-top: 1px solid rgba(175, 4, 232, 0.2);
            padding-top: 1.5rem;
        }
        .article-date-block {
            width: 120px;
            min-width: 120px;
            background: var(--primary);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-align: center;
            height: 120px;
            margin-right: 1.5rem;
        }
        .article-date-day {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            font-weight: bold;
            line-height: 1;
            margin-bottom: 0.3rem;
        }
        .article-date-month {
            font-family: 'Exo 2', sans-serif;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .article-date-meta {
            display: flex;
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 0.4rem;
        }
        .article-date-meta span {
            display: flex;
            align-items: center;
            margin-right: 1rem;
        }
        .article-date-meta i {
            margin-right: 0.3rem;
            font-size: 0.8rem;
        }
        .article-content-wrap {
            flex: 1;
        }
        .article-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            margin-bottom: 0.7rem;
            color: #fff;
            text-decoration: none;
            transition: color 0.2s;
            display: block;
        }
        .article-title:hover {
            color: var(--primary);
        }
        .article-excerpt {
            color: #aaa;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.2rem;
        }
        .read-more {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            padding: 0.5rem 1.5rem;
            text-decoration: none;
            font-family: 'Exo 2', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: background 0.2s;
        }
        .read-more:hover {
            background: #9200c5;
        }
        @media (max-width: 1100px) {
            .articles-main-wrapper {
                flex-direction: column;
                align-items: stretch;
                gap: 2rem;
            }
            .article-item {
                flex-direction: column;
            }
            .article-date-block {
                margin-bottom: 1rem;
                width: 100%;
                height: 80px;
                flex-direction: row;
            }
            .article-date-day {
                margin-right: 0.5rem;
                margin-bottom: 0;
            }
        }
    </style>
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