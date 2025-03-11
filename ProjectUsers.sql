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
    api_id VARCHAR(255) NOT NULL UNIQUE,
    date_created DATETIME NOT NULL,
    role_id INT(3) UNSIGNED ZEROFILL,
    type_id INT(3) UNSIGNED ZEROFILL,
    date_updated DATETIME NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(role_id),
    FOREIGN KEY (type_id) REFERENCES tbl_type(type_id)
);

-- Insert default roles if they don't exist
INSERT IGNORE INTO roles (role_name) VALUES ('admin'), ('staff'), ('owner');

-- Insert default types if they don't exist
INSERT IGNORE INTO tbl_type (type_name) VALUES ('delivery'), ('business'), ('supplier');