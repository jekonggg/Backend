<?php
session_start(); // Start session

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
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

// Fetch session user details from database
$userID = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE UserID = $userID";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $fullName = $user['firstname'] . ' ' . $user['lastname']; // Adjust as per your database structure
} else {
    // Handle case where user is not found (though session should have a valid user_id)
    $fullName = "Unknown";
}

// Fetch all users for table display
$sqlAllUsers = "SELECT * FROM Users";
$resultAllUsers = $conn->query($sqlAllUsers);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="adminPage_Users.css" rel="stylesheet">
    <link href="adminPage_Dashboard.css" rel="stylesheet">

    <script>
        function filterUsers(role) {
            var rows = document.querySelectorAll("#userTable tr[data-role]");
            rows.forEach(function(row) {
                if (role === 'all' || row.getAttribute('data-role') === role) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            var links = document.querySelectorAll(".filter-link");
            links.forEach(function(link) {
                link.classList.remove('selected');
            });
            document.querySelector(".filter-link[onclick='filterUsers(\"" + role + "\")']").classList.add('selected');
        }
    </script>
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
        <a href="adminPage_Dashboard.php" class="rise-button" style="margin-top: 50px;">Dashboard</a><br>
        <a href="adminPage_Products.php" class="rise-button">Products</a><br>
        <a href="adminPage_Orders.php" class="rise-button">Orders</a><br>
        <a href="adminPage_Users.php" class="rise-button selected">Users</a><br>
    </div>
    <div class="vertical-line"></div>

    <h1>USERS</h1>

    <div class="filter-links">
        <a href="javascript:void(0)" class="filter-link selected" onclick="filterUsers('all')">All</a>
        <a href="javascript:void(0)" class="filter-link" onclick="filterUsers('Admin')">Admin</a>
        <a href="javascript:void(0)" class="filter-link" onclick="filterUsers('User')">User</a>
    </div>

    <table id="userTable">
        <tr>
            <th>UserID</th>
            <th>Username</th>
            <th>Email</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Birthday</th>
            <th>Contact No</th>
            <th>Address</th>
            <th>Role</th>
        </tr>
        <?php
        if ($resultAllUsers->num_rows > 0) {
            while ($row = $resultAllUsers->fetch_assoc()) {
                echo "<tr data-role=\"" . htmlspecialchars($row['Role']) . "\">";
                echo "<td>" . $row['UserID'] . "</td>";
                echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['middlename']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                echo "<td>" . htmlspecialchars($row['birthday']) . "</td>";
                echo "<td>" . htmlspecialchars($row['contactno']) . "</td>";
                echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Role']) . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>

</body>
</html>
