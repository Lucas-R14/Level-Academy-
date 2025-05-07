-- Criação da base de dados
CREATE DATABASE IF NOT EXISTS level_academy;
USE level_academy;

-- Tabela de administradores
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de contatos
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserindo um administrador padrão (senha: admin123)
-- A senha está hasheada usando password_hash() do PHP
INSERT INTO admins (username, password, email)
VALUES ('admin', '$2y$10$8Mm/2FmQ7DEPnRV2Y.rbuuUC/7ZBU7GiLbsu1UWsA0K5iUQOdknHW', 'admin@levelacademy.com.br'); 