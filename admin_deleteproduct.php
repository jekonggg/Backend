<?php
session_start();
require_once('dbconnection.php');

// Ensure user is logged in and is an admin
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

// Check if product ID is set
if (isset($_GET['id'])) {
    $productID = $conn->real_escape_string($_GET['id']);

    // Delete product compatible vehicles
    $deleteCompatibleVehicles = "DELETE FROM ProductCompatibleVehicles WHERE ProductID = ?";
    $stmt = $conn->prepare($deleteCompatibleVehicles);
    $stmt->bind_param("i", $productID);
    $stmt->execute();

    // Delete product
    $deleteProduct = "DELETE FROM Product WHERE ProductID = ?";
    $stmt = $conn->prepare($deleteProduct);
    $stmt->bind_param("i", $productID);

    if ($stmt->execute()) {
        echo "Product deleted successfully.";
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid product ID.";
}

// Close the connection
$conn->close();

// Redirect back to the view products page
header("Location: admin_viewproducts.php");
exit();
?>
