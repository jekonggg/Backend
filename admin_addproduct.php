<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 20px;
            margin-left: 200px;
            margin-right: 200px;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 0 auto;
        }
        input[type=text],
        input[type=number],
        textarea,
        select {
            width: calc(100% - 12px);
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        input[type=file] {
            width: 100%;
            margin-top: 8px;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        a {
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            color: #333;
            padding: 10px 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        a:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h1>Add Product</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="productName">Product Name:</label><br>
        <input type="text" id="productName" name="productName" required><br><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" required></textarea><br><br>
        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br><br>
        <label for="stock">Stock:</label><br>
        <input type="number" id="stock" name="stock" required><br><br>
        <label for="categoryID">Category:</label><br>
        <select id="categoryID" name="categoryID" required>
            <?php foreach ($categories as $categoryID => $categoryName) { ?>
                <option value="<?php echo $categoryID; ?>"><?php echo $categoryName; ?></option>
            <?php } ?>
        </select><br><br>
        <label for="brandID">Brand:</label><br>
        <select id="brandID" name="brandID" required>
            <?php foreach ($brands as $brandID => $brandName) { ?>
                <option value="<?php echo $brandID; ?>"><?php echo $brandName; ?></option>
            <?php } ?>
        </select><br><br>
        <label for="variation">Variation:</label><br>
        <input type="text" id="variation" name="variation"><br><br>
        <label>Compatible Vehicle:</label><br>
        <?php foreach ($carTypes as $carTypeID => $carType) { ?>
            <input type="checkbox" id="compatibleVehicle_<?php echo $carTypeID; ?>" name="compatibleVehicle[]" value="<?php echo $carTypeID; ?>">
            <label for="compatibleVehicle_<?php echo $carTypeID; ?>"><?php echo $carType; ?></label><br>
        <?php } ?>
        <br>
        <label for="productImages">Product Images:</label><br>
        <input type="file" id="productImages" name="productImages[]" multiple required><br><br>
        <input type="submit" name="submit" value="Add Product">
    </form>
    <a href="admin_dashboard.php">Home</a>
</body>
</html>
