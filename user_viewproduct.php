<?php
session_start();
require_once('dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'Admin') {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the product ID from the URL
$productId = $_GET['id'];

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the product details from the database
$sql = "SELECT p.ProductName, p.Description, p.Price, p.Variation,
            p.Stock, c.CategoryName, b.BrandName, p.ProductImages
        FROM Product p
        JOIN Category c ON p.CategoryID = c.CategoryID
        JOIN Brand b ON p.BrandID = b.BrandID
        WHERE p.ProductID = $productId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $productImages = explode(",", $row["ProductImages"]);
    $firstImage = $productImages[0];

    // Retrieve the compatible vehicle types for the product
    $sql2 = "SELECT ct.CarType
            FROM ProductCompatibleVehicles pcv
            JOIN car_types ct ON pcv.CarTypeID = ct.CarTypeID
            WHERE pcv.ProductID = $productId";
    $result2 = $conn->query($sql2);
    $compatibleVehicles = [];
    while ($row2 = $result2->fetch_assoc()) {
        $compatibleVehicles[] = $row2["CarType"];
    }
    $compatibleVehiclesList = implode(", ", $compatibleVehicles);
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Product Details</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
                margin: 0;
                padding: 20px;
            }
            .product-container {
                max-width: 800px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .product-container h1 {
                margin-top: 0;
            }
            .product-container .product-info {
                display: flex;
            }
            .product-image {
                max-width: 300px;
                margin-right: 40px;
            }
            .product-info-details {
                flex: 1;
            }
            .product-info p {
                margin: 5px 0;
            }
            .product-info label {
                font-weight: bold;
            }
            .product-info form {
                margin-top: 10px;
            }
            .product-info form input[type="number"] {
                width: 60px;
            }
            .product-info form button {
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                margin-right: 10px;
            }
            .product-info form button:hover {
                background-color: #45a049;
            }
            .back-button {
                display: block;
                margin-top: 20px;
                text-align: center;
                text-decoration: none;
                padding: 10px 20px;
                background-color: #ccc;
                color: #333;
                border-radius: 4px;
            }
            .back-button:hover {
                background-color: #999;
            }
        </style>
    </head>
    <body>
        <div class="product-container">
            <h1>Product Details</h1>

            <div class="product-info">
                <img class="product-image" src="<?php echo $firstImage; ?>" alt="<?php echo $row["ProductName"]; ?>">
                <div class="product-info-details">
                    <h2><?php echo $row["ProductName"]; ?></h2>
                    <p><strong>Description:</strong> <?php echo $row["Description"]; ?></p>
                    <p><strong>Price:</strong> $<?php echo $row["Price"]; ?></p>
                    <p><strong>Variation:</strong> <?php echo $row["Variation"]; ?></p>
                    <p><strong>Stock:</strong> <?php echo $row["Stock"]; ?></p>
                    <p><strong>Category:</strong> <?php echo $row["CategoryName"]; ?></p>
                    <p><strong>Brand:</strong> <?php echo $row["BrandName"]; ?></p>
                    <p><strong>Compatible Vehicles:</strong> <?php echo $compatibleVehiclesList; ?></p>
                    <form method="post" action="user_viewcart.php">
                        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $row['Stock']; ?>">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                    <form method="post" action="user_checkout.php">
                        <input type="hidden" name="product_id" value="<?php echo $productId; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" name="buy_now">Buy Now</button>
                    </form>
                </div>
            </div>
            <a class="back-button" href="javascript:history.back()">Back</a>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Product not found.";
}

$conn->close();
?>
