<?php
session_start();
require_once('dbconnection.php');

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Check if user ID is provided and is a valid integer
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("User ID not specified or invalid.");
}

$user_id = $_GET['id'];

// Initialize database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare delete query
$delete_sql = "DELETE FROM Users WHERE UserID = ?";
$stmt = $conn->prepare($delete_sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

// Execute delete query
if ($stmt->execute()) {
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('User deleted successfully');</script>";
    } else {
        echo "User not found or already deleted.";
    }
} else {
    echo "Error deleting user: " . $stmt->error;
}

// Close statement and database connection
$stmt->close();
$conn->close();

// Redirect back to admin view users page
header("Location: admin_viewuser.php");
exit();
?>
