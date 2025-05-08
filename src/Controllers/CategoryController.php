<?php
require_once '../config/config.php';

class CategoryController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Get all categories
    public function getAll() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM categories ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching categories: " . $e->getMessage());
        }
    }
    
    // Get single category
    public function get($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching category: " . $e->getMessage());
        }
    }
    
    // Create new category
    public function create($name) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([htmlspecialchars($name)]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating category: " . $e->getMessage());
        }
    }
    
    // Update category
    public function update($id, $name) {
        try {
            $stmt = $this->pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
            return $stmt->execute([htmlspecialchars($name), $id]);
        } catch (PDOException $e) {
            throw new Exception("Error updating category: " . $e->getMessage());
        }
    }
    
    // Delete category
    public function delete($id) {
        try {
            // First check if category is used in articles
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM articles WHERE Category = (SELECT name FROM categories WHERE id = ?)");
            $stmt->execute([$id]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
                throw new Exception("Cannot delete category because it's being used in articles.");
            }
            
            $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error deleting category: " . $e->getMessage());
        }
    }
}
