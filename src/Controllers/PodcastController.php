<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Controllers/User.php';
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
        // Initialize User
        $user = new User(getPDO());

        // Ensure user is logged in and is admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            $_SESSION['error'] = 'You do not have permission to perform this action';
            header('Location: login.php');
            exit();
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
