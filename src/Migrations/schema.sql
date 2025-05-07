-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS level_academy;
USE level_academy;

-- Users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO users (username, email, password, role)
SELECT 'admin', 'admin@example.com', 'admin', 'admin'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin');

-- Articles table
CREATE TABLE IF NOT EXISTS articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Podcasts table
CREATE TABLE IF NOT EXISTS podcasts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    youtube_link VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tournaments table
CREATE TABLE IF NOT EXISTS tournaments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    prize VARCHAR(100) NOT NULL,
    entry_fee DECIMAL(10,2) NOT NULL,
    registration_link VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add indexes for better performance
-- Check if index exists before creating
SET @sql = IF((SELECT COUNT(*) FROM information_schema.statistics 
               WHERE table_schema = DATABASE() 
               AND table_name = 'articles' 
               AND index_name = 'idx_articles_author') = 0,
              'CREATE INDEX idx_articles_author ON articles(author_id)',
              'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.statistics 
               WHERE table_schema = DATABASE() 
               AND table_name = 'podcasts' 
               AND index_name = 'idx_podcasts_title') = 0,
              'CREATE INDEX idx_podcasts_title ON podcasts(title)',
              'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql = IF((SELECT COUNT(*) FROM information_schema.statistics 
               WHERE table_schema = DATABASE() 
               AND table_name = 'tournaments' 
               AND index_name = 'idx_tournaments_event_date') = 0,
              'CREATE INDEX idx_tournaments_event_date ON tournaments(event_date)',
              'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
