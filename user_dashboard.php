<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
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
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
            padding: 50px;
        }
        h1 {
            margin-bottom: 50px;
        }
        .btn {
            display: inline-block;
            margin: 20px;
            padding: 15px 30px;
            font-size: 18px;
            color: white;
            background-color: #4CAF50;
            text-decoration: none;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to Your Dashboard</h1>
        <a class="btn" href="user_viewaccount.php">View Account</a>
        <a class="btn" href="user_viewproducts.php">View Products</a>
        <a class="btn" href="user_viewcart.php">View Cart</a>
        <a class="btn" href="user_vieworder.php">View Orders</a>
    </div>
</body>
</html>
