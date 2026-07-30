-- =============================================
-- ShopX Global — Database Schema
-- MySQL 5.7+ / MariaDB 10.3+
-- =============================================

CREATE DATABASE IF NOT EXISTS shopx_global
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE shopx_global;

-- =============================================
-- Users
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    wallet_balance DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    is_suspended TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB;

-- =============================================
-- Categories
-- =============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- Products
-- =============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(500) DEFAULT NULL,
    price DECIMAL(12, 2) NOT NULL,
    original_price DECIMAL(12, 2) DEFAULT NULL,
    stock INT NOT NULL DEFAULT 100,
    rating DECIMAL(2, 1) DEFAULT 4.5,
    review_count INT DEFAULT 0,
    is_hot TINYINT(1) DEFAULT 0,
    discount_percent INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    INDEX idx_category (category_id),
    INDEX idx_price (price),
    INDEX idx_is_hot (is_hot),
    INDEX idx_active (is_active),
    FULLTEXT idx_search (title, description)
) ENGINE=InnoDB;

-- =============================================
-- Carts
-- =============================================
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    session_id VARCHAR(128) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_session (session_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_product (cart_id, product_id)
) ENGINE=InnoDB;

-- =============================================
-- Orders
-- =============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
    total DECIMAL(12, 2) NOT NULL,
    tracking_code VARCHAR(50) DEFAULT NULL,
    shipping_address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL DEFAULT 'wallet',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_orders (user_id),
    INDEX idx_status (status),
    INDEX idx_tracking (tracking_code)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    qty INT NOT NULL,
    price_at_purchase DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- Gift Cards
-- =============================================
CREATE TABLE IF NOT EXISTS gift_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) DEFAULT NULL,
    initial_value DECIMAL(12, 2) NOT NULL,
    remaining_value DECIMAL(12, 2) NOT NULL,
    purchased_by INT DEFAULT NULL,
    redeemed_by INT DEFAULT NULL,
    status ENUM('active', 'used', 'voided') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (purchased_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (redeemed_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_code (code),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- =============================================
-- Wallet Transactions
-- =============================================
CREATE TABLE IF NOT EXISTS wallet_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('deposit', 'purchase', 'refund', 'gift_card') NOT NULL,
    amount DECIMAL(12, 2) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    reference_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_wallet (user_id),
    INDEX idx_type (type)
) ENGINE=InnoDB;

-- =============================================
-- Virtual Stores
-- =============================================
CREATE TABLE IF NOT EXISTS virtual_stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    logo_url VARCHAR(500) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_owner (owner_user_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS virtual_store_products (
    store_id INT NOT NULL,
    product_id INT NOT NULL,
    PRIMARY KEY (store_id, product_id),
    FOREIGN KEY (store_id) REFERENCES virtual_stores(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- Wishlists
-- =============================================
CREATE TABLE IF NOT EXISTS wishlists (
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- Chat Messages
-- =============================================
CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    session_id VARCHAR(128) DEFAULT NULL,
    message TEXT NOT NULL,
    sender ENUM('user', 'admin', 'bot') NOT NULL DEFAULT 'user',
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_chat (user_id),
    INDEX idx_session_chat (session_id),
    INDEX idx_sender (sender)
) ENGINE=InnoDB;

-- =============================================
-- Admin Online Status (Heartbeat)
-- =============================================
CREATE TABLE IF NOT EXISTS admin_online_status (
    admin_id INT PRIMARY KEY,
    last_heartbeat INT NOT NULL,
    is_online TINYINT(1) DEFAULT 1,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;


