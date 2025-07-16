CREATE DATABASE IF NOT EXISTS password_manager;
USE password_manager;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS passwords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    site VARCHAR(100) NOT NULL,
    site_user VARCHAR(100) NOT NULL,
    site_pass VARCHAR(100) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
