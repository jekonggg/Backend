<?php
session_start();
include 'dbconnection.php'; // Database connection file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['userid']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Retrieve admin's details
$userId = $_SESSION['user_id'];
$query = "SELECT firstname, lastname, userimage FROM Users WHERE UserID = $userId";
$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    $fullName = $admin['firstname'] . ' ' . $admin['lastname'];
    $userImage = $admin['userimage'];
} else {
    // Handle error
    $fullName = 'Admin Name'; // Default value
    $userImage = 'default-user-image.jpg'; // Default image path
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Templates.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile">
            <img class="profilePicture" src="Repositories/<?php echo $userImage; ?>">
            <div class="name-container">
                <p class="name"><?php echo $fullName; ?></p>
                <p class="position">Admin</p>
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

    <!-- Rest of your admin dashboard HTML content -->

</body>
</html>
