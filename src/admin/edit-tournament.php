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

// Function to properly decode HTML content 
function decodeContent($content) {
    // First level decode
    $decoded = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    // Fix common double-encoded entities
    $replacements = [
        '&amplt;' => '<',
        '&ampgt;' => '>',
        '&ampamp;' => '&',
        '&ampquot;' => '"',
        '&lt;br' => '<br',
        '&gt;' => '>',
        '&amplt' => '<',
        '&ampgt' => '>',
        '&ampamp' => '&',
        '&ampquot' => '"'
    ];
    
    foreach ($replacements as $search => $replace) {
        $decoded = str_replace($search, $replace, $decoded);
    }
    
    // Final decode pass
    return html_entity_decode($decoded, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .article-hero {
            background-image: url('../../public/assets/images/articles/banner.jpg');
            background-size: cover;
            background-position: center;
            padding: 8rem 0 6rem 0;
            position: relative;
        }
        
        .article-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to right, rgba(0,0,0,0.9), rgba(0,0,0,0.7));
            z-index: 1;
        }
        
        .article-title-main {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-top: 0;
            margin-bottom: 1rem;
            color: #fff;
            position: relative;
            z-index: 2;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .article-meta {
            display: flex;
            justify-content: center;
            font-size: 1rem;
            color: #ccc;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }
        
        .article-meta span {
            display: flex;
            align-items: center;
            margin: 0 1rem;
        }
        
        .article-meta i {
            margin-right: 0.5rem;
            color: var(--primary);
        }
        
        .article-main-wrapper {
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
        
        .article-content-section {
            flex: 1;
            max-width: 900px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            padding: 2.5rem;
            margin-top: -4rem;
            position: relative;
            z-index: 3;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 1px solid rgba(175, 4, 232, 0.2);
        }
        
        .article-content {
            color: #ddd;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        
        .article-content p {
            margin-bottom: 1.5rem;
        }
        
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        
        .article-share {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(175, 4, 232, 0.2);
        }
        
        .article-share-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            color: #fff;
            margin-bottom: 1rem;
        }
        
        .article-share-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary);
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .share-btn:hover {
            transform: translateY(-3px);
            background: var(--secondary);
        }
        
        .back-to-articles {
            display: inline-block;
            margin-top: 2rem;
            color: var(--primary);
            text-decoration: none;
            font-family: 'Exo 2', sans-serif;
            font-size: 1rem;
            transition: color 0.2s;
        }
        
        .back-to-articles i {
            margin-right: 0.5rem;
        }
        
        .back-to-articles:hover {
            color: var(--secondary);
        }
        
        @media (max-width: 1100px) {
            .article-title-main {
                font-size: 2.2rem;
                padding: 0 1rem;
            }
            
            .article-meta {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .article-meta span {
                margin: 0.5rem;
            }
            
            .article-main-wrapper {
                flex-direction: column;
                align-items: stretch;
                gap: 2rem;
            }
            
            .article-content-section {
                padding: 1.5rem;
                margin-top: -2rem;
            }
        }
    </style>
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
                // Usar a função personalizada para descodificar corretamente o conteúdo
                echo decodeContent($article['content']);
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