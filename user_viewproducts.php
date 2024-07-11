<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .container {
            max-width: 1200px;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .product-container {
            border: 1px solid #ccc;
            background-color: #fff;
            width: 300px;
            margin: 10px;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }
        .product-container:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .product-info {
            overflow: hidden;
        }
        .product-info h2 {
            margin-top: 0;
            font-size: 1.2em;
        }
        .product-info p {
            margin: 5px 0;
        }
        .product-info strong {
            font-weight: bold;
        }
        .product-link {
            color: #333;
            text-decoration: none;
            display: block;
            overflow: hidden;
        }
        .product-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Products</h1>
        <?php
        include('dbconnection.php');

        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve products from the database
        $sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, c.CategoryName, b.BrandName, p.ProductImages 
                FROM Product p
                JOIN Category c ON p.CategoryID = c.CategoryID
                JOIN Brand b ON p.BrandID = b.BrandID
                ORDER BY p.ProductID";
        $result = $conn->query($sql);

        // Display the products
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productImages = explode(",", $row["ProductImages"]);
                $firstImage = isset($productImages[0]) ? $productImages[0] : 'Repositories/stock.jpg'; // Default image path if no images found
                echo "<div class='product-container'>";
                echo "<a class='product-link' href='user_viewproduct.php?id=" . $row["ProductID"] . "'>";
                
                echo "<img class='product-image' src='" . $firstImage . "' alt='" . $row["ProductName"] . "'>";
                
                echo "<div class='product-info'>";
                echo "<h2>" . $row["ProductName"] . "</h2>";
                echo "<p><strong>Description:</strong> " . $row["Description"] . "</p>";
                echo "<p><strong>Price:</strong> $" . $row["Price"] . "</p>";
                echo "<p><strong>Category:</strong> " . $row["CategoryName"] . "</p>";
                echo "<p><strong>Brand:</strong> " . $row["BrandName"] . "</p>";
                echo "</div>"; // Close product-info
                echo "</a>"; // Close product-link
                echo "</div>"; // Close product-container
            }
        } else {
            echo "<p>No products found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
