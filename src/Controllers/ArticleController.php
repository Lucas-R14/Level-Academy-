<?php
require_once '../config/config.php';

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
    
    // Create new article
    public function create($title, $content, $author, $category) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO articles (title, content, author, Category) VALUES (?, ?, ?, ?)");
            $stmt->execute([htmlspecialchars($title), nl2br(htmlspecialchars($content)), htmlspecialchars($author), htmlspecialchars($category)]);
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
    public function update($id, $data) {
        try {
            $result = executeQuery(
                "UPDATE articles 
                SET title = ?, 
                    content = ?,
                    author = ?
                WHERE id = ?",
                [
                    htmlspecialchars($data['title']),
                    nl2br(htmlspecialchars($data['content'])),
                    htmlspecialchars($data['author']),
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
