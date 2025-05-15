<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Controllers/User.php';

class ArticleController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Get all categories
    public function getCategories() {

        try {
            $result = executeQuery("SELECT Id as id, Name as name FROM category ORDER BY Name");
            if ($result['success']) {
                return $result['results'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            error_log("Error in getCategories: " . $e->getMessage());
            return [];
        }
    }

    // Verify if category exists
    private function categoryExists($categoryId) {
        try {
            $result = executeQuery(
                "SELECT * FROM categories
                 WHERE Id = ?",
                [(int)$categoryId]
            );
            if ($result['success'] && !empty($result['results'])) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error in categoryExists: " . $e->getMessage());
            return false;
        }
    }
    
    // Create new article
    public function create($title, $content, $author, $category) {

        // Initialize User
        $user = new User(getPDO());

        // Ensure user is logged in and is admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            $_SESSION['error'] = 'You do not have permission to perform this action';
            header('Location: login.php');
            exit();
        }

        try {
            // Verify category exists
            if (!$this->categoryExists($category)) {
                throw new Exception("Invalid category ID");
            }

            $stmt = $this->pdo->prepare("INSERT INTO articles (title, content, author, Category) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                htmlspecialchars($title),
                nl2br(htmlspecialchars($content)),
                htmlspecialchars($author),
                (int)$category
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating article: " . $e->getMessage());
        }
    }
    
    // Get all articles (for admin)
    public function getAll() {
        try {
            $result = executeQuery("SELECT * FROM articles ORDER BY created_at DESC");
            if ($result['success']) {
                return $result['results'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching articles: " . $e->getMessage());
        }
    }
    
    // Get all articles (for public)
    public function getAllPublished() {
        try {
            $result = executeQuery("SELECT * FROM articles ORDER BY created_at DESC");
            if ($result['success']) {
                return $result['results'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching articles: " . $e->getMessage());
        }
    }
    
    // Get total number of articles
    public function getTotalArticles() {
        try {
            $result = executeQuery("SELECT COUNT(*) as total FROM articles");
            if ($result['success']) {
                return $result['results'][0]['total'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error getting total articles: " . $e->getMessage());
        }
    }

    // Get paginated articles
    public function getPaginatedArticles($offset, $limit) {
        try {
            $result = executeQuery(
                "SELECT * FROM articles 
                ORDER BY created_at DESC 
                LIMIT ?, ?",
                [$offset, $limit]
            );
            
            if ($result['success']) {
                return $result['results'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching paginated articles: " . $e->getMessage());
        }
    }
    
    // Get single article
    public function get($id) {
        try {
            $result = executeQuery(
                "SELECT * FROM articles WHERE id = ?",
                [$id]
            );
            
            if ($result['success'] && !empty($result['results'])) {
                return $result['results'][0];
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("Error fetching article: " . $e->getMessage());
        }
    }
    
    // Update article
    public function update($id, $title, $content, $author, $category) {
        
        // Initialize User
        $user = new User(getPDO());

        // Ensure user is logged in and is admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            $_SESSION['error'] = 'You do not have permission to perform this action';
            header('Location: login.php');
            exit();
        }

        try {
            // Verify category exists
            if (!$this->categoryExists($category)) {
                throw new Exception("Invalid category ID");
            }

            $result = executeQuery(
                "UPDATE articles 
                SET title = ?, 
                    content = ?,
                    author = ?,
                    Category = ?
                WHERE id = ?",
                [
                    htmlspecialchars($title),
                    nl2br(htmlspecialchars($content)),
                    htmlspecialchars($author),
                    (int)$category,
                    $id
                ]
            );
            
            if ($result['success']) {
                return $result['affected_rows'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error updating article: " . $e->getMessage());
        }
    }
    
    // Delete article
    public function delete($id) {
        // Initialize User
        $user = new User(getPDO());

        // Ensure user is logged in and is admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            $_SESSION['error'] = 'You do not have permission to perform this action';
            header('Location: login.php');
            exit();
        }

        try {
            $result = executeQuery(
                "DELETE FROM articles WHERE id = ?",
                [$id]
            );
            
            if ($result['success']) {
                return $result['affected_rows'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error deleting article: " . $e->getMessage());
        }
    }
}