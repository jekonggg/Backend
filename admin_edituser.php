<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 20px;
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
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button[type=submit]:hover {
            background-color: #45a049;
        }
        a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        a:hover {
            background-color: #ddd;
        }
        #other_address {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Admin - Edit User</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$user_id"; ?>" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

        <label for="firstname">First Name:</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>

        <label for="middlename">Middle Name:</label>
        <input type="text" id="middlename" name="middlename" value="<?php echo htmlspecialchars($middlename); ?>">

        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>

        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" name="birthday" value="<?php echo htmlspecialchars($birthday); ?>">

        <label for="contactno">Contact Number:</label>
        <input type="text" id="contactno" name="contactno" value="<?php echo htmlspecialchars($contactno); ?>" required>

        <label for="address">Address:</label>
        <select id="address" name="address" onchange="checkOther(this.value)">
            <option value="">Select Address</option>
            <?php
            // Fetch addresses for dropdown
            $address_query = "SELECT * FROM addresses";
            $address_result = $conn->query($address_query);
            if ($address_result && $address_result->num_rows > 0) {
                while ($address_row = $address_result->fetch_assoc()) {
                    $selected = ($address_row['id'] == $address_id) ? "selected" : "";
                    echo "<option value='".$address_row['id']."' $selected>".$address_row['address'].', '.$address_row['city'].', '.$address_row['province'].', '.$address_row['zip']."</option>";
                }
            }
            ?>
            <option value="other">Other</option>
        </select>

        <div id="other_address">
            <label for="new_address">New Address:</label>
            <input type="text" id="new_address" name="new_address">

            <label for="new_city">City:</label>
            <input type="text" id="new_city" name="new_city">

            <label for="new_province">Province:</label>
            <input type="text" id="new_province" name="new_province">

            <label for="new_zip">ZIP:</label>
            <input type="text" id="new_zip" name="new_zip">
        </div>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="User" <?php if ($role == 'User') echo 'selected'; ?>>User</option>
            <option value="Admin" <?php if ($role == 'Admin') echo 'selected'; ?>>Admin</option>
        </select>

        <label for="userImage">User Image:</label>
        <input type="file" id="userImage" name="userImage"><br><br>

        <button type="submit" name="update_user">Update User</button>
    </form>

    <a href="admin_viewuser.php">Back to Users</a>

    <script>
        function checkOther(value) {
            var otherAddressDiv = document.getElementById('other_address');
            if (value === 'other') {
                otherAddressDiv.style.display = 'block';
            } else {
                otherAddressDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
