<?php
session_start();
require_once('dbconnection.php');

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Initialize database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user ID and new role are provided
if (!isset($_GET['id']) || empty($_GET['id']) || !isset($_GET['role']) || empty($_GET['role'])) {
    die("User ID or role not specified.");
}

$user_id = $_GET['id'];
$new_role = $_GET['role'];

// Update user role in the database
$update_sql = "UPDATE Users SET Role = '$new_role' WHERE UserID = $user_id";

if ($conn->query($update_sql) === TRUE) {
    echo "<script>alert('User role updated successfully');</script>";
    // Redirect back to the admin view users page
    header("Location: admin_viewuser.php");
    exit();
} else {
    echo "Error updating user role: " . $conn->error;
}

// Close database connection
$conn->close();
?>
