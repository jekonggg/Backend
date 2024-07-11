<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="userPage_cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
  <?php include 'userPage_header_main.php'; ?>
    
    <div class="container">
        <div class="cart-details">
            <h2>Cart</h2>
            <p>You have a total of 3 items in your cart</p>
            
            <div class="cart-item">
                <input type="checkbox" checked>
                <img src="placeholder-image.png" alt="Product Image">
                <div class="item-info">
                    <strong>TOYODA</strong>
                    <p>Premium All-Season High-Performance Radial Automobile Tire with Enhanced Traction, Improved Fuel Efficiency, and Extended Treadwear for Optimal Safety and Driving Comfort on All Road Conditions</p>
                </div>
            </div>
            
            <div class="cart-item">
                <input type="checkbox">
                <img src="placeholder-image.png" alt="Product Image">
                <div class="item-info">
                    <strong>NIZZAN</strong>
                    <p>Premium All-Season High-Performance Radial Automobile Tire with Enhanced Traction, Improved Fuel Efficiency, and Extended Treadwear for Optimal Safety and Driving Comfort on All Road Conditions</p>
                </div>
            </div>

            <div class="cart-item">
                <input type="checkbox" checked>
                <img src="placeholder-image.png" alt="Product Image">
                <div class="item-info">
                    <strong>NIZZAN</strong>
                    <p>Premium All-Season High-Performance Radial Automobile Tire with Enhanced Traction, Improved Fuel Efficiency, and Extended Treadwear for Optimal Safety and Driving Comfort on All Road Conditions</p>
                </div>
            </div>
        </div>

        <div class="payment-details">
            <h2>Payment Details</h2>
            <p class="total">Total: P 3,200.00</p>
            <p>Shipping fee: P 40.00</p>
            <p>Total discount: P 0.00</p>
            <hr>
            <p class="total">P 3,240.00</p>
            <a href="checkout.php"><button class="checkout-button">Proceed to Checkout</button></a>
        </div>
    </div>

    <?php include 'userPage_footer.php'; ?>


</body>
</html>
