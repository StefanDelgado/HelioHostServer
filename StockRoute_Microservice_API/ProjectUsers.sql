CREATE DATABASE ProjectUsers;

USE ProjectUsers;

-- Create roles table if it doesn't exist
CREATE TABLE IF NOT EXISTS roles (
    role_id INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) UNIQUE NOT NULL
);

-- Create tbl_type table if it doesn't exist
CREATE TABLE IF NOT EXISTS tbl_type (
    type_id INT(3) UNSIGNED ZEROFILL AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) UNIQUE NOT NULL
);

-- Create users table if it doesn't exist
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    api_id VARCHAR(255) NOT NULL UNIQUE,
    date_created DATETIME NOT NULL,
    role_id INT(3) UNSIGNED ZEROFILL,
    type_id INT(3) UNSIGNED ZEROFILL,
    date_updated DATETIME NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    FOREIGN KEY (type_id) REFERENCES tbl_type(type_id)
);

-- Create microservice_users table if it doesn't exist
CREATE TABLE IF NOT EXISTS microservice_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    api_id VARCHAR(255) NOT NULL,
    date_created DATETIME NOT NULL,
    role_id INT(3) UNSIGNED ZEROFILL,
    type_id INT(3) UNSIGNED ZEROFILL,
    date_updated DATETIME NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    FOREIGN KEY (type_id) REFERENCES tbl_type(type_id)
);

-- Create products table if it doesn't exist
CREATE TABLE IF NOT EXISTS products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    supplier_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,
    category VARCHAR(255) NOT NULL,
    description TEXT,
    image_url TEXT,
    FOREIGN KEY (supplier_id) REFERENCES microservice_users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Create orders table if it doesn't exist
CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    business_id INT NOT NULL,
    supplier_id INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    order_status ENUM('Pending', 'Processing', 'Delivered', 'Cancelled') NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    delivery_date DATE,
    FOREIGN KEY (business_id) REFERENCES microservice_users(id),
    FOREIGN KEY (supplier_id) REFERENCES microservice_users(id)
);

-- Create order_items table if it doesn't exist
CREATE TABLE IF NOT EXISTS order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Insert default roles if they don't exist
INSERT IGNORE INTO roles (role_id, role_name) VALUES 
(201, 'admin'), 
(202, 'owner'), 
(203, 'staff');

-- Insert default types if they don't exist
INSERT IGNORE INTO tbl_type (type_id, type_name) VALUES 
(301, 'delivery'), 
(302, 'business'), 
(303, 'supplier');

