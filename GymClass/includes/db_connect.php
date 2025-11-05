<?php
// Start session for user login tracking
session_start();

// Database connection parameters
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'cis344_gym';

// Connect to MySQL database
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set charset to handle UTF-8
$conn->set_charset("utf8mb4");
?>
