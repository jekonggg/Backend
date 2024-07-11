<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'dbconnection.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the user information
$userId = $_SESSION['user_id']; // Assuming you store user ID in session after login
$userResult = $conn->query("SELECT * FROM Users WHERE UserID = $userId");
$user = $userResult->fetch_assoc();
$fullName = $user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, width=device-width">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="adminPage_Dashboard.css" rel="stylesheet">
    <link href="adminPage_Orders.css" rel="stylesheet">
</head>
<body>

<?php
if (isset($_GET['action']) && isset($_GET['orderID'])) {
    $action = $_GET['action'];
    $orderID = intval($_GET['orderID']);

    if ($action == 'accept') {
        $sql = "UPDATE Orders SET Status = 'Shipped' WHERE OrderID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orderID);
        if ($stmt->execute()) {
            echo "Order accepted successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif ($action == 'decline') {
        $sql = "DELETE FROM Orders WHERE OrderID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $orderID);
        if ($stmt->execute()) {
            echo "Order declined successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }

    $stmt->close();
    header("Location: adminPage_Orders.php");
    exit();
}
?>

<div class="profile-container">
    <div class="profile">
        <img class="profilePicture" src="<?php echo htmlspecialchars($user['userimage']); ?>">
        <div class="name-container">
            <p class="name"><?php echo htmlspecialchars($user['firstname'].$user['lastname']); ?></p>
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
    <a href="adminPage_Orders.php" class="rise-button selected">Orders</a><br>
    <a href="adminPage_Users.php" class="rise-button">Users</a><br>
</div>
<div class="vertical-line"></div>

<h1>MANAGE ORDERS</h1>
<div class="container">
    <div class="rectangle">
        <h2>Recent Orders</h2><br>
        <?php
        // Fetch recent orders
        $sql = "SELECT * FROM Orders WHERE Status = 'Pending'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orderID = $row['OrderID'];
                echo "<div class='order-item'>
                        <div>Order ID: {$row['OrderID']}, Total: {$row['TotalAmount']}</div>
                        <div>
                            <a href='adminPage_Orders.php?action=accept&orderID=$orderID' class='button'>Accept</a>
                            <a href='adminPage_Orders.php?action=decline&orderID=$orderID' class='button decline'>Decline</a>
                        </div>
                      </div>";
            }
        } else {
            echo "<div class='no-orders-message'>No orders.</div>";
        }
        ?>
    </div>

    <div class="rectangle">
        <h2>Orders</h2>
        <?php
        // Fetch accepted orders
        $sql = "SELECT * FROM Orders WHERE Status = 'Shipped' OR Status = 'Arrived'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='order-item'>
                        <div>Order ID: {$row['OrderID']}, Total: {$row['TotalAmount']}</div>
                      </div>";
            }
        } else {
            echo "<div class='no-orders-message'>No orders.</div>";
        }

        $conn->close();
        ?>
    </div>
</div>
</body>
</html>
