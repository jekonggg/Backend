<?php
session_start();
require_once('dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Initialize database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch all products from the database
function fetchProducts($conn) {
    $sql = "SELECT * FROM Product";
    $result = $conn->query($sql);
    return $result;
}

// Function to display product data
function displayProducts($conn, $result) {
    if ($result->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table>";
        echo "<tr><th>Product ID</th><th>Product Name</th><th>Description</th><th>Price</th><th>Stock</th><th>Category</th><th>Brand</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['ProductID']."</td>";
            echo "<td>".$row['ProductName']."</td>";
            echo "<td>".$row['Description']."</td>";
            echo "<td>$".$row['Price']."</td>";
            echo "<td>".$row['Stock']."</td>";

            // Fetch category name
            $category_id = $row['CategoryID'];
            $category_query = "SELECT CategoryName FROM Category WHERE CategoryID = $category_id";
            $category_result = $conn->query($category_query);
            if ($category_result && $category_result->num_rows > 0) {
                $category_row = $category_result->fetch_assoc();
                $category_name = $category_row['CategoryName'];
                echo "<td>".$category_name."</td>";
            } else {
                echo "<td>Category not found</td>";
            }

            // Fetch brand name
            $brand_id = $row['BrandID'];
            $brand_query = "SELECT BrandName FROM Brand WHERE BrandID = $brand_id";
            $brand_result = $conn->query($brand_query);
            if ($brand_result && $brand_result->num_rows > 0) {
                $brand_row = $brand_result->fetch_assoc();
                $brand_name = $brand_row['BrandName'];
                echo "<td>".$brand_name."</td>";
            } else {
                echo "<td>Brand not found</td>";
            }

            echo "<td>";
            echo "<a href='admin_editproduct.php?id=".$row['ProductID']."'>Edit</a> | ";
            echo "<a href='admin_deleteproduct.php?id=".$row['ProductID']."' onclick='return confirm(\"Are you sure you want to delete this product?\");'>Delete</a>";
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>No products found.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Products</title>
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
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
            white-space: nowrap;
        }
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #333;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .add-product-btn {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .add-product-btn:hover {
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
    <h1>Admin - View Products</h1>

    <a href="admin_addproduct.php" class="add-product-btn">Add New Product</a>

    <div class="container">
        <?php
        $products = fetchProducts($conn);
        displayProducts($conn, $products);
        ?>
    </div>

    <a href="admin_dashboard.php">Home</a>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
