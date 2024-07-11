<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Database connection details
include 'dbconnection.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user input from form
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password for security
$email = $_POST['email'];
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];
$birthday = $_POST['birthday'];
$contactno = $_POST['contactno'];
$address = $_POST['address']; // Replace with proper handling if using address table
$userimage = $_POST['userimage'];
$role = $_POST['role'];

// Prepare and execute SQL statement to insert new user
$sql = "INSERT INTO Users (Username, Password, Email, firstname, middlename, lastname, birthday, contactno, address, userimage, Role)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssss", $username, $password, $email, $firstname, $middlename, $lastname, $birthday, $contactno, $address, $userimage, $role);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Redirect to users page with success message
    header("Location: adminPage_Users.php?status=success");
} else {
    // Redirect to users page with error message
    header("Location: adminPage_Users.php?status=error");
}

$stmt->close();
$conn->close();
?>
