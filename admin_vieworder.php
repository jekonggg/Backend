<?php
session_start();
require_once('dbconnection.php');

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update order status functionality
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $sql = "UPDATE Orders SET Status = ? WHERE OrderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_vieworder.php");
    exit();
}

// Retrieve orders excluding "Arrived" orders
$sql = "SELECT o.OrderID, o.OrderDate, COALESCE(o.Status, 'Pending') AS Status, o.TotalAmount, u.Username 
        FROM Orders o 
        JOIN Users u ON o.UserID = u.UserID 
        WHERE o.Status != 'Arrived'"; // Exclude orders with Status = 'Arrived'
$result = $conn->query($sql);
$orders = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
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
    <title>Manage Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .order-item {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .order-item-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .order-item-info p {
            margin: 5px 0;
        }
        .update-status-form {
            display: flex;
            align-items: center;
        }
        .update-status-form select {
            margin-right: 10px;
            padding: 8px;
            font-size: 14px;
        }
        .update-status-form button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .update-status-form button:hover {
            background-color: #45a049;
        }
        .dashboard-btn {
            display: block;
            width: 100px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .dashboard-btn:hover {
            background-color: #45a049;
        }
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h1>Manage Orders</h1>
    <a href="admin_dashboard.php" class="dashboard-btn">Home</a>
    <div class="container">
        <?php if (!empty($orders)) { ?>
            <?php foreach ($orders as $order) { ?>
                <div class="order-item">
                    <div class="order-item-info">
                        <div>
                            <p><strong>Order ID:</strong> <?php echo $order['OrderID']; ?></p>
                            <p><strong>User:</strong> <?php echo $order['Username']; ?></p>
                            <p><strong>Order Date:</strong> <?php echo $order['OrderDate']; ?></p>
                            <p><strong>Order Status:</strong> <?php echo $order['Status']; ?></p>
                            <p><strong>Total Amount:</strong> $<?php echo $order['TotalAmount']; ?></p>
                        </div>
                        <form method="post" class="update-status-form">
                            <input type="hidden" name="order_id" value="<?php echo $order['OrderID']; ?>">
                            <label for="status-<?php echo $order['OrderID']; ?>">Update Status:</label>
                            <select id="status-<?php echo $order['OrderID']; ?>" name="status">
                                <option value="Pending" <?php if ($order['Status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Shipped" <?php if ($order['Status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                <option value="Arrived" <?php if ($order['Status'] == 'Arrived') echo 'selected'; ?>>Arrived</option>
                                <option value="Cancelled" <?php if ($order['Status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status">Update Status</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No orders found.</p>
        <?php } ?>
    </div>
</body>
</html>
