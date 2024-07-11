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
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product Details</title>
        <link rel="stylesheet" href="userPage_newcart.css">
        <link rel="stylesheet" href="userPage_productView.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
        <style>
            body{
                font-family: "Inter", sans-serif;
            }
            .formcontainer {
                position: fixed;
                right: -100%;
                top: 0;
                width: 30%;
                height: 100%;
                background-color: white;
                box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
                transition: right 0.3s ease;
                z-index: 1000;
                padding: 20px; /* Added padding for inner spacing */
                box-sizing: border-box; /* Ensure padding does not affect width */
            }

            .formcontainer.show {
                right: 0;
            }

            .formcontainer h2 {
                text-align: center;
                margin-top: 20px;
            }

            .cartcontainer {
                display: flex;
                padding: 10px;
                border-bottom: 1px solid #ddd;
            }

            .cartcontainer .productimage {
                width: 25%;
                height: 25%;
                background-color: #f5f5f5;
            }

            .cartcontainer .productinfo {
                width: 75%;
                padding-left: 10px;
            }

            .subtotal {
                display: flex;
                justify-content: space-between;
                padding: 10px;
                font-size: 1.2em;
                border-top: 1px solid #ddd;
                margin-top: 20px;
            }

            .formcontainer button {
                width: 100%; /* Make the button full width */
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                cursor: pointer;
                font-size: 1em;
                margin-top: 10px;
            }

            .formcontainer button:hover {
                background-color: #45a049;
            }

            .back-button {
                width: 100%;
                padding: 10px;
                background-color: #f44336;
                color: white;
                border: none;
                cursor: pointer;
                font-size: 1em;
                margin-top: 10px;
            }

            .back-button:hover {
                background-color: #e41f1f;
            }
        </style>
    </head>
    <body>

    <?php include 'userPage_header_main.php'; ?>

    <div class="main-container">
        <div class="container">
            <div class="left-container">
                <div class="image-viewer" id="imageViewer">
                    <img id="mainImage" src="<?php echo $firstImage; ?>" alt="<?php echo $row["ProductName"]; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="thumbnails">
                    <?php foreach ($productImages as $image) { ?>
                        <img src="<?php echo $image; ?>" alt="Thumbnail" onclick="changeImage('<?php echo $image; ?>')" onmouseenter="changeImage('<?php echo $image; ?>')" onmouseleave="changeImage('<?php echo $image; ?>')">
                    <?php } ?>
                </div>
            </div>
            <div class="right-container">
                <div class="product-details">
                    <div class="product-name"><?php echo $row["ProductName"]; ?></div>
                    <div class="productinfo">
                        <span style="color: #EE2222"><u>Rating: ★★★★☆</u></span> | <span>Sold: 1234</span> | <span>Reviews: 567</span>
                    </div>
                    <div class="product-price">$<?php echo $row["Price"]; ?></div>
                    <div class="product-variation">
                        <label for="variation">Select Variation:</label>
                        <select id="variation">
                            <option value="variation1">Variation 1</option>
                            <option value="variation2">Variation 2</option>
                        </select>
                    </div>
                    <div class="product-shipping">Shipping:</div>
                    <div class="shipping-address">
                        <label for="address">Shipping Address:</label>
                        <select id="address">
                            <option value="address1">Address 1</option>
                            <option value="address2">Address 2</option>
                        </select>
                    </div>
                    <div class="seller-info-container">
                        <div class="seller-logo-container">
                            <img src="Repositories/toyoda.jpg" alt="Seller Logo" class="seller-logo">
                        </div>
                        <div class="seller-name"><?php echo $row["BrandName"]; ?></div>
                    </div>
                    <div class="buttons">
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
            </div>
        </div>
    </div>

    <div class="secondary-container">
        <div class="container">
            <div class="left-container" style="flex: 2; border-right-color: 1px rgba(0, 0, 0, .54)">
                <div class="product-description">
                    <h2>Product Description</h2>
                    <p><?php echo $row["Description"]; ?></p>
                </div>
            </div>
            <div class="right-container" style="flex: 1">
                <div class="product-specs">
                    <h2>Product Specification</h2>
                    <p><strong>Category:</strong> <?php echo $row["CategoryName"]; ?></p>
                    <p><strong>Stock:</strong> <?php echo ($row["Stock"] > 0) ? 'In Stock' : 'Out of Stock'; ?></p>
                    <p><strong>Brand Name:</strong> <?php echo $row["BrandName"]; ?></p>
                    <p><strong>Compatible Vehicles:</strong> <?php echo $compatibleVehiclesList; ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="comments-section">
        <h2>Comments</h2>
        <ul class="comments-list">
            <li class="comment-item">
                <div class="username">John Doe</div>
                <div class="comment-text">Excellent product! Worth every penny. Highly recommend.</div>
            </li>
            <li class="comment-item">
                <div class="username">Jane Smith</div>
                <div class="comment-text">Good quality, but the shipping was a bit slow. Overall, satisfied.</div>
            </li>
            <!-- Add more comments here -->
        </ul>
    </div>

    <div class="rating-section">
        <h2>Ratings</h2>
        <div class="rating-item">
            <div class="rating-username">John Doe</div>
            <div class="rating-stars">★★★★☆</div>
        </div>
        <div class="rating-item">
            <div class="rating-username">Jane Smith</div>
            <div class="rating-stars">★★★☆☆</div>
        </div>
        <!-- Add more ratings here -->
    </div>

    <div class="formcontainer" id="formcontainer">
        <h2 style="justify-content: left;">CART</h2>
        <!-- Existing cart content here -->
    </div>

    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }

        const addtocartBtn = document.getElementById('addtocart');
        const formContainer = document.getElementById('formcontainer');
        const backBtn = document.getElementById('back-button');

        addtocartBtn.addEventListener('click', () => {
            formContainer.classList.add('show');
        });

        backBtn.addEventListener('click', () => {
            formContainer.classList.remove('show');
        });
    </script>

    <?php include 'userPage_footer.php'; ?>

    </body>
    </html>

    <?php
} else {
    echo "Product not found.";
}

$conn->close();
?>
