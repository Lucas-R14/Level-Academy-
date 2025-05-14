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
            $result = executeQuery("SELECT * FROM categories ORDER BY name");
            if ($result['success']) {
                return $result['results'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching categories: " . $e->getMessage());
        }
    }
    
    // Get single category
    public function get($id) {
        try {
            $result = executeQuery("SELECT * FROM categories WHERE id = ?", [$id]);
            if ($result['success']) {
                return $result['results'][0] ?? null;
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching category: " . $e->getMessage());
        }
    }
    
    // Create new category
    public function create($name) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
        }
        try {
            $result = executeQuery("INSERT INTO categories (name) VALUES (?)", [htmlspecialchars($name)]);
            if ($result['success']) {
                return true;
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error creating category: " . $e->getMessage());
        }
    }
    
    // Update category
    public function update($id, $name) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ../admin/login.php');
            exit;
        }
        try {
            $result = executeQuery("UPDATE categories SET name = ? WHERE id = ?", [htmlspecialchars($name), $id]);
            if ($result['success']) {
                return $result['affectedRows'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error updating category: " . $e->getMessage());
        }
    }
    
    // Delete category
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
            // First check if category is used in articles
            $result = executeQuery("SELECT COUNT(*) as count FROM articles WHERE Category = (SELECT name FROM categories WHERE id = ?)", [$id]);
            if ($result['success'] && $result['results'][0]['count'] > 0) {
                throw new Exception("Cannot delete category because it's being used in articles.");
            }
            
            // Delete the category
            $result = executeQuery("DELETE FROM categories WHERE id = ?", [$id]);
            if ($result['success']) {
                return $result['affectedRows'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error deleting category: " . $e->getMessage());
        }
    }
}
