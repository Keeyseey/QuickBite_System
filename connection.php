<?php
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'quickbite_db');

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, 'utf8mb4');
?>
