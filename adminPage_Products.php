<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "autosupphply";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch brands from the database
$brandsResult = $conn->query("SELECT BrandID, BrandName FROM brand");
$brands = [];
while ($row = $brandsResult->fetch_assoc()) {
    $brands[] = $row;
}

// Fetch categories from the database
$categoriesResult = $conn->query("SELECT CategoryID, CategoryName FROM category");
$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
    $categories[] = $row;
}

// Fetch the user information
$userId = $_SESSION['user_id']; // Assuming you store user ID in session after login
$userResult = $conn->query("SELECT * FROM Users WHERE UserID = $userId");
$user = $userResult->fetch_assoc();
$fullName = $user['firstname'] . ' ' . $user['middlename'] . ' ' . $user['lastname'];

// Handle add, update, and delete requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $message = "";

        if ($action == 'add') {
            $stmt = $conn->prepare("INSERT INTO Product (ProductName, Description, Price, Stock, BrandID, CategoryID) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdiis", $productName, $description, $price, $stock, $brandId, $categoryId);

            $productName = $_POST['productName'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $brandId = $_POST['brandId'];
            $categoryId = $_POST['categoryId'];

            if ($stmt->execute()) {
                $message = "New record created successfully";
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        if ($action == 'update') {
            $stmt = $conn->prepare("UPDATE Product SET ProductName = ?, Description = ?, Price = ?, Stock = ?, BrandID = ?, CategoryID = ? WHERE ProductID = ?");
            $stmt->bind_param("ssdiisi", $productName, $description, $price, $stock, $brandId, $categoryId, $productId);

            $productId = $_POST['productId'];
            $productName = $_POST['productName'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $brandId = $_POST['brandId'];
            $categoryId = $_POST['categoryId'];

            if ($stmt->execute()) {
                $message = "Record updated successfully";
            } else {
                $message = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        if ($action == 'delete') {
            // First, delete related rows in the productcompatiblevehicles table
            $stmt = $conn->prepare("DELETE FROM productcompatiblevehicles WHERE ProductID = ?");
            $stmt->bind_param("i", $productId);
            if (!$stmt->execute()) {
                $message = "Error: " . $stmt->error;
                echo "<script>alert('$message'); window.location.href = window.location.href;</script>";
                exit;
            }
            $stmt->close();

            // Now delete the product
            $stmt = $conn->prepare("DELETE FROM Product WHERE ProductID = ?");
            $stmt->bind_param("i", $productId);
            if ($stmt->execute()) {
                $message = "Record deleted successfully";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }

        echo "<script>alert('$message'); window.location.href = window.location.href;</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link href="adminPage_Dashboard.css" rel="stylesheet">
    <link href="adminPage_Products.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="profile-container">
        <div class="profile">
            <img class="profilePicture" src="<?php echo htmlspecialchars($user['userimage']); ?>">
            <div class="name-container">
                <p class="name"><?php echo htmlspecialchars($fullName); ?></p>
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
        <a href="adminPage_Products.php" class="rise-button selected" >Products</a><br>
        <a href="adminPage_Orders.php" class="rise-button">Orders</a><br>
        <a href="adminPage_Users.php" class="rise-button">Users</a><br>
    </div>
    <div class="vertical-line"></div>
    
    <h1>MANAGE PRODUCTS</h1>
    
    <div class="rectangle">
        <button onclick="showAddModal()"><img src="Repositories/add.png"></button>
        <div class="products-container">
            <?php
            $result = $conn->query("SELECT * FROM Product");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $productId = $row['ProductID'];
                    $productName = $row['ProductName'];
                    $price = $row['Price'];
                    
                    // Fetch the image filenames from the database
                    $imageFiles = $row['ProductImages']; // Ensure this matches the column name in your database
                    
                    // Default image path
                    $defaultImagePath = "Repositories/stock.jpg";
                    
                    // Determine the image path
                    if (!empty($imageFiles)) {
                        // Split the filenames by comma and get the first one
                        $imageArray = explode(',', $imageFiles);
                        $firstImageFile = trim($imageArray[0]); // Get the first filename and trim any extra whitespace
                        $imagePath = "uploads/" . $firstImageFile; // Construct the path
                    } else {
                        $imagePath = $defaultImagePath;
                    }

                    echo '<div class="product">';
                    echo '<img src="' . $imagePath . '" alt="Product Image">';
                    echo '<div class="product-details">';
                    echo '<div class="product-name">' . $productName . '</div>';
                    echo '<div class="product-price">₱' . $price . '</div>';
                    echo '<div class="action-buttons">';
                    echo '<button class="hyperlink-button" onclick="showUpdateModal(' . $productId . ', \'' . $productName . '\', \'' . $row["Description"] . '\', ' . $price . ', ' . $row["Stock"] . ', ' . $row["BrandID"] . ', ' . $row["CategoryID"] . ')">Update</button>';
                    echo '<form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="productId" value="' . $productId . '">
                            <button class="hyperlink-button" type="submit" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</button>
                          </form>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No products found.";
            }
            ?>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="hideModal('addModal')">&times;</span>
            <h2>Add Product</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="productName">Product Name:</label>
                    <input type="text" id="productName" name="productName" required>
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="stock">Stock:</label>
                    <input type="number" id="stock" name="stock" required>
                </div>
                <div class="form-group">
                    <label for="brandId">Brand:</label>
                    <select id="brandId" name="brandId" required>
                        <?php foreach ($brands as $brand) : ?>
                            <option value="<?php echo $brand['BrandID']; ?>"><?php echo $brand['BrandName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="categoryId">Category:</label>
                    <select id="categoryId" name="categoryId" required>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category['CategoryID']; ?>"><?php echo $category['CategoryName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="action" value="add">
                <button type="submit">Add Product</button>
            </form>
        </div>
    </div>

    <!-- Update Product Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="hideModal('updateModal')">&times;</span>
            <h2>Update Product</h2>
            <form method="POST" action="">
                <input type="hidden" id="updateProductId" name="productId">
                <div class="form-group">
                    <label for="updateProductName">Product Name:</label>
                    <input type="text" id="updateProductName" name="productName" required>
                </div>
                <div class="form-group">
                    <label for="updateDescription">Description:</label>
                    <textarea id="updateDescription" name="description" required></textarea>
                </div>
                <div class="form-group">
                    <label for="updatePrice">Price:</label>
                    <input type="number" id="updatePrice" name="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="updateStock">Stock:</label>
                    <input type="number" id="updateStock" name="stock" required>
                </div>
                <div class="form-group">
                    <label for="updateBrandId">Brand:</label>
                    <select id="updateBrandId" name="brandId" required>
                        <?php foreach ($brands as $brand) : ?>
                            <option value="<?php echo $brand['BrandID']; ?>"><?php echo $brand['BrandName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="updateCategoryId">Category:</label>
                    <select id="updateCategoryId" name="categoryId" required>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo $category['CategoryID']; ?>"><?php echo $category['CategoryName']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="hidden" name="action" value="update">
                <button type="submit">Update Product</button>
            </form>
        </div>
    </div>

    <script>
        function showAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function showUpdateModal(productId, productName, description, price, stock, brandId, categoryId) {
            document.getElementById('updateProductId').value = productId;
            document.getElementById('updateProductName').value = productName;
            document.getElementById('updateDescription').value = description;
            document.getElementById('updatePrice').value = price;
            document.getElementById('updateStock').value = stock;
            document.getElementById('updateBrandId').value = brandId;
            document.getElementById('updateCategoryId').value = categoryId;
            document.getElementById('updateModal').style.display = 'block';
        }

        function hideModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
