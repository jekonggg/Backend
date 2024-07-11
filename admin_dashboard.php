<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .dashboard-btn {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            width: 200px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .dashboard-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <a href="admin_addproduct.php" class="dashboard-btn">Add Product</a>
    <a href="admin_viewuser.php" class="dashboard-btn">View Users</a>
    <a href="admin_vieworder.php" class="dashboard-btn">View Orders</a>
    <a href="admin_viewproducts.php" class="dashboard-btn">View Products</a>
    <a href="logout.php" class="dashboard-btn" onclick="return confirm('Are you sure you want to logout?')">Logout</a>

</body>
</html>
