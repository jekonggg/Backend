<?php
session_start();
// Include database connection
include('dbconnection.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in and has Admin role
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$userID = $_SESSION['user_id'];
$sql = "SELECT firstname, middlename, lastname, userimage, Role FROM Users WHERE UserID = ? AND Role = 'Admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    // If no admin user found, redirect or handle the error
    header('Location: login.php');
    exit();
}

// Combine names into full name
$fullName = $user['firstname'] . ' ' . ($user['middlename'] ? $user['middlename'] . ' ' : '') . $user['lastname'];

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="adminPage_Dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="profile-container">
        <div class="profile">
            <img class="profilePicture" src="<?php echo htmlspecialchars($user['userimage']); ?>">
            <div class="name-container">
                <p class="name"><?php echo htmlspecialchars($fullName); ?></p>
                <p class="position"><?php echo htmlspecialchars($user['Role']); ?></p>
            </div>
            <div class="options">
                <hr class="header-line">
                <a href="admin_editProfile.php"><img src="Repositories/edit.png"> Edit Profile</a>
                <a href="logout.php"><img src="Repositories/logout.png">Logout</a>
            </div>
        </div>
    </div>

    <div class="logo">
        <img class="logo-horizontal" src="Repositories/logo-horizontal.png">
    </div>

    <hr class="header-line">

    <div class="menu">
        <a href="adminPage_Dashboard.php" class="rise-button selected" style="margin-top: 50px;">Dashboard</a><br>
        <a href="adminPage_Products.php" class="rise-button">Products</a><br>
        <a href="adminPage_Orders.php" class="rise-button">Orders</a><br>
        <a href="adminPage_Users.php" class="rise-button">Users</a><br>
    </div>

    <div class="vertical-line"></div>

    <h1>ADMIN DASHBOARD</h1>
    <div class="rectangle">
        <b class="heading">THERE ARE 5 NEW ORDERS</b><br>
        <b class="subheading">As of January 10, 2024, 18:52</b>
    </div>

    <div class="vertical-rectangle" style="margin-top: 25px;">
        <b class="heading">THERE ARE 5 NEW ORDERS</b><br>
        <b class="subheading">As of January 10, 2024, 18:52</b>
    </div>

    <div class="vertical-rectangle" style="margin-left: 1090px; margin-top: -648px;">
        <b class="heading">THERE ARE 5 NEW ORDERS</b><br>
        <b class="subheading">As of January 10, 2024, 18:52</b>
    </div>

    <script>
        // JavaScript to handle dynamic name length
        document.addEventListener('DOMContentLoaded', function() {
            const profileName = document.querySelector('.name');
            const nameText = profileName.textContent;

            if (nameText.length > 16) {
                profileName.textContent = nameText.substring(0, 13) + '...';
            }
        });
    </script>
</body>
</html>