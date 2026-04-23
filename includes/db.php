<?php
// Start session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "campus_hub");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Helper: check if logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Helper: check if admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Helper: redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /campus_hub/login.php");
        exit();
    }
}

// Helper: redirect if not admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: /campus_hub/dashboard.php");
        exit();
    }
}

// Helper: clean output to prevent XSS (Task 7)
function clean($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
