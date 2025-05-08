<?php
class TournamentController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new tournament
    public function create($data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO tournaments (title, Format, event_date, location, prize, entry_fee, registration_link)
                VALUES (:title, :format, :event_date, :location, :prize, :entry_fee, :registration_link)
            ");
            
            $stmt->execute([
                ':title' => htmlspecialchars($data['title']),
                ':format' => htmlspecialchars($data['format']),
                ':event_date' => $data['event_date'],
                ':location' => htmlspecialchars($data['location']),
                ':prize' => $data['prize'],
                ':entry_fee' => $data['entry_fee'],
                ':registration_link' => htmlspecialchars($data['registration_link'])
            ]);
            
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating tournament: " . $e->getMessage());
        }
    }
    
    // Get all tournaments
    public function getAll() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM tournaments ORDER BY event_date DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching tournaments: " . $e->getMessage());
        }
    }
    
    // Get single tournament
    public function get($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM tournaments WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Error fetching tournament: " . $e->getMessage());
        }
    }
    
    // Update tournament
    public function update($id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE tournaments 
                SET title = :title, 
                    Format = :format, 
                    event_date = :event_date, 
                    location = :location, 
                    prize = :prize, 
                    entry_fee = :entry_fee, 
                    registration_link = :registration_link
                WHERE id = :id
            ");
            
            $stmt->execute([
                ':id' => $id,
                ':title' => htmlspecialchars($data['title']),
                ':format' => htmlspecialchars($data['format']),
                ':event_date' => $data['event_date'],
                ':location' => htmlspecialchars($data['location']),
                ':prize' => $data['prize'],
                ':entry_fee' => $data['entry_fee'],
                ':registration_link' => htmlspecialchars($data['registration_link'])
            ]);
            
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error updating tournament: " . $e->getMessage());
        }
    }
    
    // Delete tournament
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM tournaments WHERE id = :id");
            $stmt->execute([':id' => $id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw new Exception("Error deleting tournament: " . $e->getMessage());
        }
    }
    
    // Get total number of tournaments
    public function getTotalTournaments() {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tournaments");
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new Exception("Error counting tournaments: " . $e->getMessage());
        }
    }
}
