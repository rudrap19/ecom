<?php
$host = 'localhost';
$db = 'BOOK';
$user = 'root';
$pass = ''; // Replace with actual password if needed

// Improved database connection with error handling
try {
    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4"); // Set UTF-8 encoding for proper character support
} catch (Exception $e) {
    die("Error: " . $e->getMessage()); // Display a user-friendly error message
}
?>
