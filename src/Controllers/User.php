<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

class User {
    private $db;
    private $user_id;
    private $username;
    private $email;
    private $role;

    public function __construct($db) {
        $this->db = $db;
    }

    private $salt_length = 32;

    private function generateSalt() {
        return bin2hex(random_bytes($this->salt_length / 2));
    }

    private function hashPassword($password, $salt) {
        // Combine salt and password, then hash with bcrypt
        $salted_password = $salt . $password;
        return [
            'salt' => $salt,
            'hashed_password' => password_hash($salted_password, PASSWORD_BCRYPT)
        ];
    }

    public function register($username, $password, $email, $role = 'user') {
        // Initialize User
        $user = new User(getPDO());

        // Ensure user is logged in and is admin
        if (!$user->isLoggedIn() || !$user->isAdmin()) {
            $_SESSION['error'] = 'You do not have permission to perform this action';
            header('Location: login.php');
            exit();
        }
        
        try {
            $salt = $this->generateSalt();
            $password_data = $this->hashPassword($password, $salt);

            $result = executeQuery(
                "INSERT INTO users (username, password, salt, email, role) VALUES (?, ?, ?, ?, ?)",
                [$username, $password_data['hashed_password'], $password_data['salt'], $email, $role]
            );
            
            if ($result['success']) {
                return $result['last_insert_id'];
            }
            throw new Exception($result['error']);
        } catch (Exception $e) {
            throw new Exception("Error registering user: " . $e->getMessage());
        }
    }

    public function authenticate($username, $password) {
        try {
            // Ensure database connection is available
            if (!$this->db) {
                error_log('Database connection not available in User class');
                return false;
            }

            $stmt = $this->db->prepare("SELECT id, username, password, salt, role, email FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($user['salt'] . $password, $user['password'])) {
                // Start session if not already started
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                // Store all user data in session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'login_time' => time()
                ];
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Database error during authentication: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log('Authentication error: ' . $e->getMessage());
            return false;
        }
    }

    public function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['user']['id']);
    }

    public function getUserData() {
        if ($this->isLoggedIn()) {
            $result = executeQuery(
                "SELECT * FROM users WHERE id = ?",
                [$_SESSION['user_id']]
            );

            if ($result['success'] && !empty($result['results'])) {
                return $result['results'][0];
            }
            return false;
        }
        return false;
    }

    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
    }
    
    public function isAdmin() {
        return $this->isLoggedIn() && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
    }
}
