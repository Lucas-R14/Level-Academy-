<?php
// Load environment variables with fallback to default values
$envPath = __DIR__ . '/../.Env';
if (!file_exists($envPath)) {
    error_log("Warning: Could not find .Env file at $envPath");
    $env = [
        'DB_HOST' => 'localhost',
        'DB_USER' => 'root',
        'DB_PASS' => 'mysql',
        'DB_NAME' => 'level_academy'
    ];
} else {
    $env = parse_ini_file($envPath);
    if ($env === false) {
        error_log("Warning: Could not parse .Env file at $envPath");
        $env = [
            'DB_HOST' => 'localhost',
            'DB_USER' => 'root',
            'DB_PASS' => 'mysql',
            'DB_NAME' => 'level_academy'
        ];
    }
}

// Debug logging
error_log("Environment variables loaded:");
foreach ($env as $key => $value) {
    error_log("$key: $value");
}

// Database configuration
if (!defined('DB_HOST')) define('DB_HOST', $env['DB_HOST']);
if (!defined('DB_USER')) define('DB_USER', $env['DB_USER']);
if (!defined('DB_PASS')) define('DB_PASS', $env['DB_PASS']);
if (!defined('DB_NAME')) define('DB_NAME', $env['DB_NAME']);

// Base URL configuration
if (!defined('BASE_URL')) {
    // Try to detect the base URL automatically
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script_name);
    define('BASE_URL', $protocol . '://' . $host . $path . '/');
}

// Application configuration
if (!defined('APP_NAME')) define('APP_NAME', 'Level Academy');
if (!defined('APP_VERSION')) define('APP_VERSION', '1.0.0');

// Session configuration
if (!defined('SESSION_LIFETIME')) define('SESSION_LIFETIME', 3600); // 1 hour in seconds

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
    session_set_cookie_params(SESSION_LIFETIME);
    session_start();
}

// Create database connection
function getDatabaseConnection() {
    try {
        // First create the database if it doesn't exist
        $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        // Create database
        $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        
        // Switch to the database
        $pdo->exec("USE " . DB_NAME);
        
        // Create tables
        $pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            salt VARCHAR(64) NOT NULL,
            role ENUM('admin', 'user') DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS articles (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            author VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS podcasts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            youtube_link VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS tournaments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            event_date DATE NOT NULL,
            location VARCHAR(255) NOT NULL,
            prize VARCHAR(100) NOT NULL,
            entry_fee DECIMAL(10,2) NOT NULL,
            registration_link VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Create admin user if it doesn't exist
        $salt = '9974e364b11119f156fcf11488687199';
        $hashed_password = hash('sha256', $salt . 'admin');
        
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, salt, role) 
            SELECT 'admin', 'admin@example.com', ?, ?, 'admin' 
            WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin')");
        $stmt->execute([$hashed_password, $salt]);
        
        return $pdo;
    } catch(PDOException $e) {
        die("Database setup failed: " . $e->getMessage());
    }
}

// Secure query execution function
function executeQuery($query, $params = []) {
    try {
        $pdo = getPDO();
        
        // Sanitize input parameters
        $sanitized_params = array_map(function($param) {
            // Remove dangerous characters
            $param = preg_replace('/[\\x00-\\x1F\\x7F\\x80-\\x9F]/', '', $param);
            // Remove SQL injection patterns
            $param = preg_replace('/--|;|#|\\/\\*|\\*|\\//', '', $param);
            return $param;
        }, $params);
        
        // Prepare and execute the query
        $stmt = $pdo->prepare($query);
        $stmt->execute($sanitized_params);
        
        // Return result based on query type
        if (preg_match('/^(?:INSERT|UPDATE|DELETE)/i', $query)) {
            return [
                'success' => true,
                'affected_rows' => $stmt->rowCount(),
                'last_insert_id' => $pdo->lastInsertId()
            ];
        } else {
            return [
                'success' => true,
                'results' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        }
    } catch (PDOException $e) {
        return [
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ];
    }
}

// Get PDO connection (singleton pattern)
function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = getDatabaseConnection();
    }
    return $pdo;
}

// Get current user data
function getCurrentUser() {
    if (isset($_SESSION['user_id'])) {
        $result = executeQuery(
            "SELECT * FROM users WHERE id = ?",
            [$_SESSION['user_id']]
        );
        if ($result['success'] && !empty($result['results'])) {
            return $result['results'][0];
        }
    }
    return null;
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['role'] === 'admin';
}

// Redirect function
function redirect($path) {
    $fullPath = preg_match('/^https?:\/\//', $path) ? $path : BASE_URL . ltrim($path, '/');
    header("Location: $fullPath");
    exit();
}

// Error handling
function handleError($error) {
    echo "<div class='error'>" . htmlspecialchars($error) . "</div>";
}

// Success message
function showSuccess($message) {
    echo "<div class='success'>" . htmlspecialchars($message) . "</div>";
}

// Session is already initialized at the top of the file
// No need to call initSession() again
?>
