<?php
class PodcastController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new podcast
    public function create($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO podcasts (title, description, youtube_link)
                VALUES (:title, :description, :youtube_link)
            ");
            
            $stmt->execute([
                ':title' => htmlspecialchars($data['title']),
                ':description' => htmlspecialchars($data['description']),
                ':youtube_link' => htmlspecialchars($data['youtube_link'])
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating podcast: " . $e->getMessage());
        }
    }
    
    // Get all podcasts
    public function getAll() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM podcasts ORDER BY created_at DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching podcasts: " . $e->getMessage());
        }
    }
    
    // Get single podcast
    public function get($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM podcasts WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching podcast: " . $e->getMessage());
        }
    }
    
    // Update podcast
    public function update($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE podcasts 
                SET title = :title, 
                    description = :description, 
                    youtube_link = :youtube_link
                WHERE id = :id
            ");
            
            $stmt->execute([
                ':id' => $id,
                ':title' => htmlspecialchars($data['title']),
                ':description' => htmlspecialchars($data['description']),
                ':youtube_link' => htmlspecialchars($data['youtube_link'])
            ]);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error updating podcast: " . $e->getMessage());
        }
    }
    
    // Delete podcast
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM podcasts WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error deleting podcast: " . $e->getMessage());
        }
    }
    
    // Get total number of podcasts
    public function getTotalPodcasts() {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM podcasts");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Error counting podcasts: " . $e->getMessage());
        }
    }
}
