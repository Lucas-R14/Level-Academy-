<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'mysql');
define('DB_NAME', 'level_academy');

// Create database connection
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
?>
