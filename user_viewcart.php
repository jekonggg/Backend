<?php
session_start();
require_once('dbconnection.php');

// Database connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'Admin') {
    // Redirect to login page or appropriate page
    header("Location: login.php");
    exit();
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Add to cart functionality
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header("Location: user_viewcart.php");
    exit();
}

// Update quantity functionality
if (isset($_POST['update_quantity'])) {
    foreach ($_POST['quantities'] as $product_id => $quantity) {
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]); // Remove item from cart if quantity is 0 or less
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }
    header("Location: user_viewcart.php");
    exit();
}

// Fetch cart items from database
$cart_items = array();
$total_price = 0; // Initialize total price

if (!empty($_SESSION['cart'])) {
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM Product WHERE ProductID IN ($product_ids)";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $row['quantity'] = $_SESSION['cart'][$row['ProductID']];
            $row['total_price'] = $row['Price'] * $_SESSION['cart'][$row['ProductID']]; // Calculate total price for each item
            $cart_items[] = $row;
            $total_price += $row['total_price']; // Accumulate total price
        }
    }
}

// Checkout functionality
if (isset($_POST['checkout'])) {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Check if the user ID exists in the Users table
    $sql = "SELECT * FROM Users WHERE UserID = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "User ID not found in the Users table.";
        exit();
    }

    // Retrieve selected cart items for checkout
    $checkout_items = $_POST['checkout_items'];
    $cart_items_to_checkout = array_intersect_key($_SESSION['cart'], array_flip($checkout_items));

    if (empty($cart_items_to_checkout)) {
        echo "Please select items to checkout.";
        exit();
    }

    // Calculate total amount for selected items
    $total_amount = 0;
    foreach ($cart_items_to_checkout as $product_id => $quantity) {
        $sql = "SELECT Price FROM Product WHERE ProductID = $product_id";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_amount += $row['Price'] * $quantity;
        }
    }

    // Insert order details into Orders table
    $order_date = date('Y-m-d H:i:s');
    $order_status = 'Pending';
    $sql = "INSERT INTO Orders (UserID, OrderDate, TotalAmount, Status) VALUES ($user_id, '$order_date', $total_amount, '$order_status')";

    if ($conn->query($sql) === TRUE) {
        $order_id = $conn->insert_id;

        // Insert selected cart items into OrderItem table
        foreach ($cart_items_to_checkout as $product_id => $quantity) {
            $sql = "SELECT Price FROM Product WHERE ProductID = $product_id";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $price = $row['Price'];
                $sql = "INSERT INTO OrderItem (OrderID, ProductID, Quantity, Price) VALUES ($order_id, $product_id, $quantity, $price)";
                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                    exit();
                }
            }
        }

        // Clear the cart
        unset($_SESSION['cart']);

        // Redirect to order confirmation page
        header("Location: user_vieworder.php?order_id=" . $order_id);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="userPage_cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            margin-top: 140px;

        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .cart-details, .payment-details {
            margin-bottom: 20px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }
        .cart-item img {
            max-width: 100px;
            margin-right: 20px;
        }
        .item-info {
            flex: 1;
        }
        .item-info h2 {
            margin: 0;
            font-size: 18px;
        }
        .item-info p {
            margin: 5px 0;
            color: #666;
        }
        .cart-item-actions {
            text-align: right;
        }
        .cart-summary {
            text-align: right;
        }
        .cart-buttons {
            text-align: right;
        }
        .cart-buttons button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        .cart-buttons button:hover {
            background-color: #45a049;
        }
        .empty-cart {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .empty-cart p {
            margin: 0;
            font-size: 18px;
        }
        .payment-details {
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .payment-details p {
            margin: 5px 0;
            font-size: 16px;
        }
        .payment-details .total {
            font-weight: bold;
        }
        .checkout-button {
            display: block;
            width: 100%;
            padding: 10px 0;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
        }
        .checkout-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
  <?php include 'userPage_header_main.php'; ?>
    
    <div class="container">
        <div class="cart-details">
            <h2>Cart</h2>
            <p>You have a total of <?php echo count($cart_items); ?> items in your cart</p>
            
            <form method="post" action="user_viewcart.php" onsubmit="return validateCheckout()">
                <?php foreach ($cart_items as $item) { ?>
                    <div class="cart-item">
                        <input type="checkbox" name="checkout_items[]" value="<?php echo $item['ProductID']; ?>">
                        <img src="<?php echo $item['ProductImages']; ?>" alt="<?php echo $item['ProductName']; ?>">
                        <div class="item-info">
                            <strong><?php echo $item['ProductName']; ?></strong>
                            <p><?php echo $item['ProductDescription']; ?></p>
                            <p>Price: $<?php echo $item['Price']; ?></p>
                            <label for="quantity-<?php echo $item['ProductID']; ?>">Quantity:</label>
                            <input type="number" id="quantity-<?php echo $item['ProductID']; ?>" name="quantities[<?php echo $item['ProductID']; ?>]" value="<?php echo $item['quantity']; ?>" min="1">
                        </div>
                    </div>
                <?php } ?>

                <div class="cart-summary">
                    <h3>Total Price: $<?php echo $total_price; ?></h3>
                    <div class="cart-buttons">
                        <button type="submit" name="update_quantity">Update Quantity</button>
                        <button type="submit" name="checkout">Checkout Selected Items</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="payment-details">
            <h2>Payment Details</h2>
            <p class="total">Total: $<?php echo $total_price; ?></p>
            <p>Shipping fee: $40.00</p>
            <p>Total discount: $0.00</p>
            <hr>
            <p class="total">$<?php echo $total_price + 40; ?></p>
            <a href="checkout.php"><button class="checkout-button">Proceed to Checkout</button></a>
        </div>
    </div>

    <?php include 'userPage_footer.php'; ?>
</body>
</html>

<script>
    function validateCheckout() {
        var checkboxes = document.getElementsByName('checkout_items[]');
        var checked = false;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                checked = true;
                break;
            }
        }
        if (!checked) {
            alert('Please select items to checkout.');
            return false;
        }
        return true;
    }
</script>
