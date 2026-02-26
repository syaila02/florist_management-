-- Script untuk membuat database dan tabel florist
CREATE DATABASE IF NOT EXISTS florist_db;
USE florist_db;

CREATE TABLE IF NOT EXISTS flowers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('Fresh Flower', 'Pipe Cleaner Flower', 'Artificial Flower') NOT NULL,
    flower_name VARCHAR(100) NOT NULL,
    product_type ENUM('Satuan', 'Buket') NOT NULL,
    target_gender ENUM('Cowok', 'Cewek') DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contoh data awal (opsional)
INSERT INTO flowers (category, flower_name, product_type, target_gender, price, stock) VALUES 
('Fresh Flower', 'Mawar Merah', 'Satuan', NULL, 15000, 50),
('Pipe Cleaner Flower', 'Tulip Pink', 'Buket', 'Cewek', 125000, 10),
('Artificial Flower', 'Sun Flower Classic', 'Buket', 'Cowok', 85000, 5);