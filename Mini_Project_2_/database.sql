CREATE DATABASE IF NOT EXISTS assignment_system;
USE assignment_system;

CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(100) NOT NULL,
 email VARCHAR(100) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','student') NOT NULL DEFAULT 'student',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
 id INT AUTO_INCREMENT PRIMARY KEY,
 category_name VARCHAR(100) NOT NULL UNIQUE,
 description TEXT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE assignments (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(200) NOT NULL,
 description TEXT NOT NULL,
 category_id INT NOT NULL,
 created_by INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(category_id) REFERENCES categories(id) ON UPDATE CASCADE,
 FOREIGN KEY(created_by) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE submissions (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 assignment_id INT NOT NULL,
 tech_stack VARCHAR(255) NOT NULL,
 description TEXT NOT NULL,
 file_path VARCHAR(255) NOT NULL,
 file_name VARCHAR(255) NOT NULL,
 file_size INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY(assignment_id) REFERENCES assignments(id) ON DELETE CASCADE
);

INSERT INTO categories(category_name,description) VALUES
('Cybersecurity & Networking','Network tools, security audits, and defensive scripts'),
('Internet of Things (IoT)','Hardware and software integration projects'),
('Mobile Application','Native and cross-platform mobile applications'),
('Web Application','Full stack web systems built with PHP, MySQL, Bootstrap, AJAX');
