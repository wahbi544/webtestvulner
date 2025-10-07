CREATE DATABASE IF NOT EXISTS vulnerable_site;
USE vulnerable_site;

CREATE TABLE users (
    id INT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(50)
);

INSERT INTO users (id, username, password) VALUES 
(1, 'admin', 'admin123'),
(2, 'user1', 'password1'),
(3, 'test', 'test123');

CREATE TABLE products (
    id INT PRIMARY KEY,
    name VARCHAR(100),
    price DECIMAL(10,2)
);

INSERT INTO products (id, name, price) VALUES
(1, 'Product A', 19.99),
(2, 'Product B', 29.99);
