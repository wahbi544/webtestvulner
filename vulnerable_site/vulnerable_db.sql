-- إنشاء قاعدة البيانات والجداول
CREATE DATABASE vulnerable_db;
USE vulnerable_db;

-- جدول المستخدمين بكلمات مرور ضعيفة
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50),
    password VARCHAR(50),
    email VARCHAR(100),
    is_admin INT DEFAULT 0
);

-- جدول المنتجات
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    price DECIMAL(10,2),
    description TEXT
);

-- إدخال بيانات مصابة
INSERT INTO users (username, password, email, is_admin) VALUES 
('admin', 'admin123', 'admin@test.com', 1),
('user1', 'password1', 'user1@test.com', 0),
('test', 'test', 'test@test.com', 0);

INSERT INTO products (name, price, description) VALUES 
('Product 1', 19.99, 'First product'),
('Product 2', 29.99, 'Second product'),
('Test Product', 9.99, 'Test description');
