-- Création de la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS db_ecommerce;
USE db_ecommerce;

-- Table des catégories
CREATE TABLE IF NOT EXISTS categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    category_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE IF NOT EXISTS products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    product_title VARCHAR(255) NOT NULL,
    product_desc TEXT,
    product_price DECIMAL(10,2) NOT NULL,
    discounted_price DECIMAL(10,2),
    product_img VARCHAR(255),
    is_new BOOLEAN DEFAULT FALSE,
    is_trending BOOLEAN DEFAULT FALSE,
    is_top_rated BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- Table des bannières
CREATE TABLE IF NOT EXISTS banner (
    banner_id INT PRIMARY KEY AUTO_INCREMENT,
    banner_title VARCHAR(255) NOT NULL,
    banner_subtitle VARCHAR(255),
    banner_image VARCHAR(255),
    banner_items_price DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table de la barre de catégories
CREATE TABLE IF NOT EXISTS category_bar (
    category_bar_id INT PRIMARY KEY AUTO_INCREMENT,
    category_title VARCHAR(100) NOT NULL,
    category_img VARCHAR(255),
    category_quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion des catégories de base
INSERT INTO categories (category_name, category_description) VALUES
('Clothes', 'All types of clothing items'),
('Footwear', 'Shoes, boots, and other footwear'),
('Jewelry', 'Necklaces, rings, and other jewelry items'),
('Perfume', 'Fragrances and perfumes'),
('Cosmetics', 'Makeup and beauty products'),
('Glasses', 'Eyewear and sunglasses'),
('Bags', 'Handbags, backpacks, and other bags');

-- Insertion d'une bannière exemple
INSERT INTO banner (banner_title, banner_subtitle, banner_image, banner_items_price) VALUES
('Summer Collection', 'New Arrivals', 'banner1.jpg', 29.99);

-- Insertion des catégories dans la barre
INSERT INTO category_bar (category_title, category_img, category_quantity) VALUES
('Clothes', 'clothes.png', 10),
('Footwear', 'footwear.png', 8),
('Jewelry', 'jewelry.png', 5),
('Perfume', 'perfume.png', 7),
('Cosmetics', 'cosmetics.png', 12),
('Glasses', 'glasses.png', 6),
('Bags', 'bags.png', 9); 