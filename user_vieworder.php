<?php
session_start();
require_once('dbconnection.php');

// Database connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'Admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve all orders for the user
$sql = "SELECT * FROM Orders WHERE UserID = $user_id ORDER BY OrderDate DESC";
$result = $conn->query($sql);
$orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $order_id = $row['OrderID'];

        // Retrieve order items for each order
        $sql_items = "SELECT oi.*, p.ProductName, p.ProductImages FROM OrderItem oi JOIN Product p ON oi.ProductID = p.ProductID WHERE oi.OrderID = $order_id";
        $result_items = $conn->query($sql_items);
        $order_items = [];

        if ($result_items && $result_items->num_rows > 0) {
            while ($item = $result_items->fetch_assoc()) {
                $order_items[] = $item;
            }
        }

        // Add order details and items to orders array
        $row['order_items'] = $order_items;
        $orders[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .order-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .order {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ccc;
        }
        .order p {
            margin: 5px 0;
        }
        .order-items {
            margin-top: 10px;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
        .order-item {
            display: flex;
            margin-bottom: 10px;
        }
        .order-item img {
            max-width: 80px;
            margin-right: 10px;
        }
        .order-item-info {
            flex: 1;
        }
        .order-summary {
            text-align: right;
            margin-top: 10px;
        }
        .order-buttons {
            margin-top: 10px;
            text-align: right;
        }
        .order-buttons a {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .order-buttons a:hover {
            background-color: #45a049;
        }
        .empty-orders {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .empty-orders p {
            margin: 0;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="order-container">
        <h1>Order History</h1>
        <?php if (!empty($orders)) { ?>
            <?php foreach ($orders as $order) { ?>
                <div class="order">
                    <p><strong>Order ID:</strong> <?php echo $order['OrderID']; ?></p>
                    <p><strong>Order Date:</strong> <?php echo $order['OrderDate']; ?></p>
                    <p><strong>Order Status:</strong> <?php echo $order['Status']; ?></p>
                    <div class="order-items">
                        <?php foreach ($order['order_items'] as $item) { ?>
                            <div class="order-item">
                                <img src="<?php echo $item['ProductImages']; ?>" alt="<?php echo $item['ProductName']; ?>">
                                <div class="order-item-info">
                                    <h3><?php echo $item['ProductName']; ?></h3>
                                    <p><strong>Price:</strong> $<?php echo $item['Price']; ?></p>
                                    <p><strong>Quantity:</strong> <?php echo $item['Quantity']; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="order-summary">
                        <h3><strong>Total Amount:</strong> $<?php echo $order['TotalAmount']; ?></h3>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="empty-orders">
                <p>No orders found.</p>
            </div>
        <?php } ?>
        <div class="order-buttons">
            <a href="user_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
