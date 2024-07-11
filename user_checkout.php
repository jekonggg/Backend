<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .cart-item {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cart-item img {
            max-width: 100px;
            margin-right: 20px;
        }
        .cart-summary {
            text-align: right;
            margin-top: 20px;
        }
        .cart-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cart-actions button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
        .cart-actions a button {
            background-color: #f0f0f0;
            color: #333;
        }
        .cart-actions button:hover {
            background-color: #45a049;
        }
        .cart-actions a button:hover {
            background-color: #ccc;
        }
        .empty-cart {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <div class="cart-container">
            <?php if (!empty($cart_items)) { ?>
                <form method="post" action="user_checkout.php">
                    <?php foreach ($cart_items as $item) { ?>
                        <div class="cart-item">
                            <img src="<?php echo $item['ProductImages']; ?>" alt="<?php echo $item['ProductName']; ?>">
                            <div class="cart-item-info">
                                <h2><?php echo $item['ProductName']; ?></h2>
                                <p>Price: $<?php echo $item['Price']; ?></p>
                                <p>Quantity: <?php echo $_SESSION['cart'][$item['ProductID']]; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="cart-summary">
                        <h3>Total Price: $<?php echo $total_price; ?></h3>
                        <div class="cart-actions">
                            <button type="submit" name="checkout">Complete Purchase</button>
                            <a href="user_viewcart.php"><button type="button">View Cart</button></a>
                        </div>
                    </div>
                </form>
            <?php } else { ?>
                <div class="empty-cart">
                    <p>Your cart is empty.</p>
                    <a href="user_viewcart.php"><button type="button">View Cart</button></a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
