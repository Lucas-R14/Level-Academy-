<?php
require_once '../config/config.php';
class PodcastController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Get total number of podcasts
    public function getTotalPodcasts() {
        try {
            $result = executeQuery("SELECT COUNT(*) FROM podcasts");
            if ($result['success']) {
                return $result['results'][0]['COUNT(*)'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error counting podcasts: " . $e->getMessage());
        }
    }
    
    // Create new podcast
    public function create($data) {
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
                "INSERT INTO podcasts (title, youtube_link)
                VALUES (:title, :youtube_link)",
                [
                    ':title' => htmlspecialchars($data['title']),
                    ':youtube_link' => htmlspecialchars($data['youtube_link'])
                ]
            );
            if ($result['success']) {
                return $result['lastInsertId'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error creating podcast: " . $e->getMessage());
        }
    }
    
    // Get all podcasts
    public function getAll() {
        try {
            $result = executeQuery("SELECT * FROM podcasts ORDER BY created_at DESC");
            if ($result['success']) {
                return $result['results'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching podcasts: " . $e->getMessage());
        }
    }
    
    // Get single podcast
    public function get($id) {
        try {
            $result = executeQuery("SELECT * FROM podcasts WHERE id = :id", [':id' => $id]);
            if ($result['success']) {
                return $result['results'][0] ?? null;
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching podcast: " . $e->getMessage());
        }
    }
    
    // Update podcast
    public function update($id, $data) {
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
                "UPDATE podcasts 
                SET title = :title, 
                    youtube_link = :youtube_link
                WHERE id = :id",
                [
                    ':id' => $id,
                    ':title' => htmlspecialchars($data['title']),
                    ':youtube_link' => htmlspecialchars($data['youtube_link'])
                ]
            );
            if ($result['success']) {
                return $result['affectedRows'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error updating podcast: " . $e->getMessage());
        }
    }
    
    // Delete podcast
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
            $result = executeQuery("DELETE FROM podcasts WHERE id = :id", [':id' => $id]);
            if ($result['success']) {
                return $result['affectedRows'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error deleting podcast: " . $e->getMessage());
        }
    }


    

}
