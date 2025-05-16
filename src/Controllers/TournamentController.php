<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../Controllers/User.php';

class TournamentController {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Create new tournament
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
            // Get the formatted start time or use the provided one
            $start_time = !empty($data['formatted_start_time']) ? $data['formatted_start_time'] : ($data['start_time'] ?? null);
            
            $result = executeQuery(
                "INSERT INTO tournaments (title, Format, event_date, start_time, location, prize, entry_fee, registration_link, image_path)
                VALUES (:title, :format, :event_date, :start_time, :location, :prize, :entry_fee, :registration_link, :image_path)",
                [
                    ':title' => htmlspecialchars($data['title']),
                    ':format' => htmlspecialchars($data['format']),
                    ':event_date' => $data['event_date'],
                    ':start_time' => $start_time,
                    ':location' => htmlspecialchars($data['location']),
                    ':prize' => $data['prize'],
                    ':entry_fee' => $data['entry_fee'],
                    ':registration_link' => htmlspecialchars($data['registration_link']),
                    ':image_path' => $data['image_path'] ?? null
                ]
            );
            if ($result['success']) {
                return $result['lastInsertId'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error creating tournament: " . $e->getMessage());
        }
    }
    
    // Get all tournaments
    public function getAll() {
        try {
            $result = executeQuery("SELECT * FROM tournaments ORDER BY event_date DESC");
            if ($result['success']) {
                return $result['results'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching tournaments: " . $e->getMessage());
        }
    }
    
    // Get single tournament
    public function get($id) {
        try {
            $result = executeQuery("SELECT * FROM tournaments WHERE id = :id", [':id' => $id]);
            if ($result['success']) {
                return $result['results'][0] ?? null;
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error fetching tournament: " . $e->getMessage());
        }
    }
    
    // Update tournament
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
                "UPDATE tournaments 
                SET title = :title, 
                    Format = :format, 
                    event_date = :event_date, 
                    start_time = :start_time,
                    location = :location, 
                    prize = :prize, 
                    entry_fee = :entry_fee, 
                    registration_link = :registration_link,
                    image_path = :image_path
                WHERE id = :id",
                [
                    ':id' => $id,
                    ':title' => htmlspecialchars($data['title']),
                    ':format' => htmlspecialchars($data['format']),
                    ':event_date' => $data['event_date'],
                    ':start_time' => !empty($data['formatted_start_time']) ? $data['formatted_start_time'] : ($data['start_time'] ?? null),
                    ':location' => htmlspecialchars($data['location']),
                    ':prize' => $data['prize'],
                    ':entry_fee' => $data['entry_fee'],
                    ':registration_link' => htmlspecialchars($data['registration_link']),
                    ':image_path' => $data['image_path'] ?? null
                ]
            );
            if ($result['success']) {
                return $result['affectedRows'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error updating tournament: " . $e->getMessage());
        }
    }
    
    // Delete tournament
    public function delete($id) {


        $user = new User(getPDO());

        // Ensure user is logged in and is admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            $_SESSION['error'] = 'You do not have permission to perform this action';
            header('Location: login.php');
            exit();
        }


        try {
            $result = executeQuery("DELETE FROM tournaments WHERE id = :id", [':id' => $id]);
            if ($result['success']) {
                return $result['affectedRows'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error deleting tournament: " . $e->getMessage());
        }
    }
    
    // Get total number of tournaments
    public function getTotalTournaments() {
        try {
            $result = executeQuery("SELECT COUNT(*) FROM tournaments");
            if ($result['success']) {
                return $result['results'][0]['COUNT(*)'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error counting tournaments: " . $e->getMessage());
        }
    }
}
