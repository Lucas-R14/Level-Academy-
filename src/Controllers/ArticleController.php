<?php

require_once '../config/config.php';   

class ArticleController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Get all categories
    public function getCategories() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
        }

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
            $this->showAlert($categoryId);
            $this->showAlert(!empty($result['results']));
            if ($result['success'] && !empty($result['results'])) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            error_log("Error in categoryExists: " . $e->getMessage());
            return false;
        }
    }

    function showAlert($message) {
        echo "<script>alert('" . htmlspecialchars($message, ENT_QUOTES) . "');</script>";
    }
    
    // Create new article
    public function create($title, $content, $author, $category) {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
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

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
        }
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
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
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

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
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