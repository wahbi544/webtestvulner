-- vulnerable_app.sql
CREATE DATABASE IF NOT EXISTS vuln_app CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE vuln_app;

-- Användartabell (obs: plaintext password -> avsiktligt osäkert)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, email) VALUES
('alice', 'password123', 'alice@example.local'),
('bob', 'qwerty', 'bob@example.local'),
('admin', 'adminpass', 'admin@example.local');

-- Enkel produkt/tabell för sökning (sårbar mot SQLi via sökparameter)
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  description TEXT,
  price DECIMAL(10,2)
);

INSERT INTO products (name, description, price) VALUES
('Printer 1000', 'En nätverksskrivare', 129.99),
('Router X', 'Kraftfull SOHO-router', 79.00),
('NAS Basic', 'Lagringsenhet 2TB', 199.00);

