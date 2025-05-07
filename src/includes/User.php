<?php
require_once '../config/config.php';

class User {
    private $db;
    private $user_id;
    private $username;
    private $email;
    private $role;
    private $salt_length = 32;

    public function __construct($db) {
        $this->db = $db;
    }

    private function generateSalt() {
        return bin2hex(random_bytes($this->salt_length / 2));
    }

    private function hashPassword($password, $salt) {
        return hash('sha256', $salt . $password);
    }

    public function register($username, $password, $email, $role = 'user') {
        try {
            $salt = $this->generateSalt();
            $hashed_password = $this->hashPassword($password, $salt);

            $result = executeQuery(
                "INSERT INTO users (username, password, salt, email, role) VALUES (?, ?, ?, ?, ?)",
                [$username, $hashed_password, $salt, $email, $role]
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
            $result = executeQuery(
                "SELECT id, password, salt FROM users WHERE username = ?",
                [$username]
            );

            if ($result['success'] && !empty($result['results'])) {
                $user = $result['results'][0];
                if ($this->hashPassword($password, $user['salt']) === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("Error authenticating user: " . $e->getMessage());
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
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
        session_destroy();
        unset($_SESSION['user_id']);
    }
}
