<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="userPage_homePage.css">
    <title>View Products</title>
</head>
<style>

        .product-image {
            max-width: 144px; /* Adjusted maximum width */
            max-height: 144px; /* Adjusted maximum height */
            margin-right: 20px;
            float: left;
        }
        .product-img {
            max-width: 100%; /* Ensure image doesn't overflow container */
            max-height: 100%; /* Ensure image doesn't overflow container */
        }
</style>
<body>
    <?php include 'userPage_header_main.php'; ?>
    <div class="carousel">
        <input type="radio" id="slide1" name="carousel" checked>
        <input type="radio" id="slide2" name="carousel">
        <input type="radio" id="slide3" name="carousel">
        <input type="radio" id="slide4" name="carousel">
    
        <div class="carousel-images">
            <div class="carousel-slide">
                <img src="Repositories/carousel1.jpg" alt="Image 1">
            </div>
            <div class="carousel-slide">
                <img src="Repositories/carousel2.jpg" alt="Image 2">
            </div>
            <div class="carousel-slide">
                <img src="Repositories/carousel3.jpg" alt="Image 3">
            </div>
            <div class="carousel-slide">
                <img src="Repositories/carousel4.jpg" alt="Image 4">
            </div>
        </div>
    
        <div class="carousel-controls">
            <label for="slide1" class="carousel-control"></label>
            <label for="slide2" class="carousel-control"></label>
            <label for="slide3" class="carousel-control"></label>
            <label for="slide4" class="carousel-control"></label>
        </div>

        <div class="carousel-nav carousel-nav-left">&lt;</div>
        <div class="carousel-nav carousel-nav-right">&gt;</div>
    </div>

    <!-- JavaScript for automatic sliding -->
    <script>
        // Function to move to the next slide
        function moveToNextSlide() {
            // Get the current slide index
            const currentSlide = document.querySelector('input[name="carousel"]:checked');
            const nextSlide = currentSlide.nextElementSibling || document.querySelector('input[name="carousel"]:first-child');
            
            // Uncheck the current slide and check the next slide
            currentSlide.checked = false;
            nextSlide.checked = true;
         }

            // Move to the next slide every 7 seconds
            setInterval(moveToNextSlide, 7000);

            // Arrow functionality
            const carouselNavLeft = document.querySelector('.carousel-nav-left');
            const carouselNavRight = document.querySelector('.carousel-nav-right');

            carouselNavLeft.addEventListener('click', moveToPreviousSlide);
            carouselNavRight.addEventListener('click', moveToNextSlide);

            // Function to move to the previous slide
            function moveToPreviousSlide() {
            // Get the current slide index
            const currentSlide = document.querySelector('input[name="carousel"]:checked');
            const previousSlide = currentSlide.previousElementSibling || document.querySelector('input[name="carousel"]:last-child');
            
            // Uncheck the current slide and check the previous slide
            currentSlide.checked = false;
            previousSlide.checked = true;
        }
    </script>
          
    <div class="categories">
        <div class="category-container">
            <h2 style="margin-right: 800px; font-size: 15px; color: #9F9F9F; margin-top: -60px;">SHOP BY CATEGORIES</h2>
            <hr style="color: #9F9F9F; margin-top: -10px; width: 100%;"></hr>

            <div class="category">
                <div class="sphere-container">
                    <div class="sphere"></div>
                    <a href="userPage_shopByCategory.php?category=For%20Cars"><img src="Repositories/shopbyCar.png" style="width: 120px; height: 100px;" alt="For Cars" class="category-image"></a>
                </div>
                <span class="category-name">For Cars</span>
            </div>

            <div class="category">
                <div class="sphere-container" style="display: inline-block; position: flex; margin-left: -220px; margin-top: 35px;"></div>
                <a href="userPage_shopByCategory.php?category=For%20Motorcycles"><img src="Repositories/shopbyMotorcycles.png" style="width: 120px; height: 75px; margin-bottom: 20px;" alt="For Motorcycles" class="category-image"></a>
                <span class="category-name">For Motorcycles</span>
            </div>

            <div class="category">
                <div class="sphere" style="position: flex; margin-left: -80px; margin-top: 35px;"></div>
                <a href="userPage_shopByCategory.php?category=Tools"><img src="Repositories/shopbyTools.png" style="width: 120px; height: 100px;" alt="Tools" class="category-image"></a>
                <span class="category-name">Tools</span>
            </div>

            <div class="category">
                <div class="sphere" style="position: flex; margin-left: 54px; margin-top: 35px;"></div>
                <a href="userPage_userPage_shopByCategory.php?category=Parts"><img src="Repositories/shopbyParts.png" style="width: 88px; height: 70px; margin-bottom: 25px" alt="Parts" class="category-image"></a>
                <span class="category-name">Parts</span>
            </div>

            <div class="category">
                <div class="sphere" style="position: flex; margin-left: 170px; margin-top: 35px;"></div>
                <a href="userPage_shopByCategory.php?category=Accessories"><img src="Repositories/shopbyAccessories.png" style="width: 80px; height: 80px; margin-left: 15px; margin-bottom: 20px" alt="Accessories"></a>
                <span class="category-name">Accessories</span>
            </div>

            <div class="category">
                <div class="sphere" style="position: flex; margin-left: 285px; margin-top: 35px;"></div>
                <a href="userPage_shopByCategory.php?category=Modifications"><img src="Repositories/shopbyModifications.png" style="width: 95px; height: 95px; margin-left: -2px; margin-bottom: 10px" alt="Modifications"></a>
                <span class="category-name">Modifications</span>
            </div>

            <div class="category">
                <div class="sphere" style="position: flex; margin-left: 400px; margin-top: 35px;"></div>
                <a href="userPage_shopByCategory.php?category=Vehicle%20Care"><img src="Repositories/shopbyVehicleCare.png" style="width: 90px; height: 75px; margin-bottom: 25px" alt="Vehicle Care"></a>
                <span class="category-name">Vehicle Care</span>
            </div>
        </div>
    </div>


    <div class="product-section" style="margin-top: 20px;">
        <h2 class="section-title">FEATURED PRODUCTS</h2>
        <div class="rectangle"></div>
        <div class="product-grid">
            <?php
            include ('dbconnection.php');

            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Retrieve products from the database, limited to 12
            $sql = "SELECT p.ProductID, p.ProductName, p.Description, p.Price, c.CategoryName, b.BrandName, p.ProductImages 
                    FROM Product p
                    JOIN Category c ON p.CategoryID = c.CategoryID
                    JOIN Brand b ON p.BrandID = b.BrandID
                    ORDER BY p.ProductID
                    LIMIT 12";
            $result = $conn->query($sql);

            // Display the products
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $productImages = explode(",", $row["ProductImages"]);
                    $firstImage = $productImages[0];
                    $imagePath = $firstImage;
                    
                    echo "<div class='product-box'>";
                    echo "<a href='userPage_productView.php?id=" . $row["ProductID"] . "'>";
                    if (file_exists($imagePath)) {
                        echo "<img src='" . $imagePath . "' alt='" . $row["ProductName"] . "' class='product-img'>";
                    } else {
                        echo "Image not found: " . $imagePath;
                    }
                    echo "<h3 class='product-name'>" . $row["ProductName"] . "</h3>";
                    echo "</a>";
                    echo "<p class='product-rating'>" . str_repeat("★", 5) . "</p>";
                    echo "<p class='product-price'>$" . $row["Price"] . "</p>";
                    // Add additional details as necessary
                    echo "</div>";
                }
            } else {
                echo "No products found.";
            }

            $conn->close();
            ?>
        </div>
    </div>
    
    <?php include "userPage_footer.php"?>
    
</body>
</html>
