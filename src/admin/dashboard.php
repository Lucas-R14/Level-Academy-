<?php
session_start();
require_once 'includes/header.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include class files
require_once dirname(__FILE__) . '/../includes/Article.php';
require_once dirname(__FILE__) . '/../includes/Tournament.php';
require_once dirname(__FILE__) . '/../includes/Podcast.php';

// Initialize classes after header.php which contains $pdo
$article = new Article($pdo);
$podcast = new Podcast($pdo);
$tournament = new Tournament($pdo);

// Get statistics
$articleCount = $article->getTotalArticles();
$podcastCount = $podcast->getTotalPodcasts();
$tournamentCount = $tournament->getTotalTournaments();
?>

<div class="content-header">
    <h2>Dashboard</h2>
    <div class="actions">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <i class="fas fa-newspaper stat-icon"></i>
        <div class="stat-details">
            <h3><?php echo $articleCount; ?></h3>
            <p>Articles</p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-podcast stat-icon"></i>
        <div class="stat-details">
            <h3><?php echo $podcastCount; ?></h3>
            <p>Podcasts</p>
        </div>
    </div>
    <div class="stat-card">
        <i class="fas fa-trophy stat-icon"></i>
        <div class="stat-details">
            <h3><?php echo $tournamentCount; ?></h3>
            <p>Tournaments</p>
        </div>
    </div>
</div>

<?php
try {
    $articles = $article->getAll();
    if ($articles) {
        echo '<h2 class="section-title">Recent Articles</h2>';
        echo '<div class="article-cards">';
        foreach ($articles as $article) {
            echo '<div class="article-card">';
            echo '<h3>' . htmlspecialchars($article['title']) . '</h3>';
            echo '<div class="article-meta">';
            echo '<span><i class="fas fa-user"></i> ' . htmlspecialchars($article['author']) . '</span>';
            echo '<span><i class="fas fa-calendar"></i> ' . date('F j, Y', strtotime($article['created_at'])) . '</span>';
            echo '</div>';
            echo '<div class="article-actions">';
            echo '<a href="edit-article.php?id=' . $article['id'] . '" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>';
            echo '<a href="delete-article.php?id=' . $article['id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this article?\')">';
            echo '<i class="fas fa-trash"></i> Delete</a>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    }
} catch (Exception $e) {
    echo '<div class="alert error">Error fetching articles: ' . $e->getMessage() . '</div>';
}
?>

<style>
    .dashboard-stats {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .stat-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 20px;
        width: 30%;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .stat-icon {
        font-size: 2.5rem;
        color: #3498db;
        margin-right: 20px;
    }
    .stat-details h3 {
        font-size: 1.8rem;
        margin: 0;
        font-weight: bold;
        color: #2c3e50;
    }
    .stat-details p {
        margin: 5px 0 0 0;
        color: #7f8c8d;
        font-size: 1rem;
    }
    .section-title {
        margin: 30px 0 20px 0;
        font-size: 1.5rem;
        color: #2c3e50;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 10px;
    }
    .article-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    .article-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        padding: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .article-card h3 {
        margin-top: 0;
        color: #2c3e50;
        font-size: 1.3rem;
    }
    .article-meta {
        color: #7f8c8d;
        margin: 10px 0;
        font-size: 0.9rem;
        display: flex;
        flex-wrap: wrap;
    }
    .article-meta span {
        margin-right: 15px;
    }
    .article-meta i {
        margin-right: 5px;
    }
    .article-actions {
        display: flex;
        justify-content: flex-start;
        margin-top: 15px;
    }
    .article-actions .btn {
        margin-right: 10px;
        display: flex;
        align-items: center;
    }
    .article-actions .btn i {
        margin-right: 5px;
    }
    
    @media (max-width: 1200px) {
        .stat-card {
            width: 48%;
        }
    }
    
    @media (max-width: 768px) {
        .stat-card {
            width: 100%;
        }
        .article-cards {
            grid-template-columns: 1fr;
        }
    }
</style>
</body>
</html>
