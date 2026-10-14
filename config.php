<?php
// config.php
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'pt_grant_db';

// Connect to MySQL
$conn = new mysqli($host, $username, $password);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Create Database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create tables
$conn->query("CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_number VARCHAR(50) UNIQUE,
    assistance_type VARCHAR(100),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    middle_name VARCHAR(100),
    address TEXT,
    is_business TINYINT(1),
    business_name VARCHAR(255) DEFAULT '',
    current_employer VARCHAR(255),
    bankruptcy VARCHAR(10),
    rent_amount DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'Pending Review',
    document_path VARCHAR(255) DEFAULT '',
    payment_method VARCHAR(50) DEFAULT '',
    payment_receipt VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$conn->query("CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_number VARCHAR(50),
    sender VARCHAR(20),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
?>
