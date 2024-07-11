<?php
session_start();
require_once('dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] === 'Admin') {
    header("Location: login.php");
    exit();
}

// Initialize database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details based on session user_id
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM Users WHERE UserID = $user_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $username = $row['Username'];
    $email = $row['Email'];
    $firstname = $row['firstname'];
    $middlename = $row['middlename'];
    $lastname = $row['lastname'];
    $birthday = $row['birthday'];
    $contactno = $row['contactno'];
    $address_id = $row['address'];
    $role = $row['Role'];
    $userImage = isset($row['userimage']) ? $row['userimage'] : null; // Check if userimage exists
} else {
    echo "User not found.";
    exit();
}

// Handle form submission to update user information
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $birthday = $_POST['birthday'];
    $contactno = $_POST['contactno'];
    $address_id = $_POST['address'];
    
    // Handle file upload for user image
    if (isset($_FILES['userImage']) && $_FILES['userImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['userImage']['tmp_name'];
        $fileName = $_FILES['userImage']['name'];
        $fileSize = $_FILES['userImage']['size'];
        $fileType = $_FILES['userImage']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $userImage = $dest_path;
        } else {
            echo "There was an error uploading the file.";
            exit();
        }
    }

    // If user chooses 'Other' address, insert new address into addresses table
    if ($address_id == 'other') {
        $address = $_POST['new_address'];
        $city = $_POST['new_city'];
        $province = $_POST['new_province'];
        $zip = $_POST['new_zip'];

        // Insert new address into addresses table
        $insert_address_sql = "INSERT INTO addresses (address, city, province, zip)
                               VALUES ('$address', '$city', '$province', '$zip')";
        if ($conn->query($insert_address_sql) === TRUE) {
            // Get the inserted address ID
            $address_id = $conn->insert_id;
        } else {
            echo "Error: " . $insert_address_sql . "<br>" . $conn->error;
            exit();
        }
    }

    // Update user information in the database
    $update_sql = "UPDATE Users SET Username = '$username', Email = '$email', firstname = '$firstname', 
                   middlename = '$middlename', lastname = '$lastname', birthday = '$birthday', 
                   contactno = '$contactno', address = '$address_id', userimage = '$userImage' WHERE UserID = $user_id";
    
    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('User information updated successfully');</script>";
        // Redirect to avoid form resubmission on refresh
        header("Location: user_viewaccount.php");
        exit();
    } else {
        echo "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View/Edit Account</title>
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
        form {
            margin: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        input, select {
            width: calc(100% - 18px);
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        img {
            max-width: 100px;
            max-height: 100px;
            display: block;
            margin-bottom: 10px;
        }
        .edit-mode {
            display: none;
        }
        .address-fields {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .address-fields label {
            flex: 1 0 100%;
        }
        .address-fields input {
            flex: 2 0 100%;
        }
        .address-fields input[type="file"] {
            flex: 1 0 100%;
            width: auto;
        }
        .address-fields select {
            flex: 2 0 100%;
        }
        .other-address {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View/Edit Account</h1>

        <!-- View Mode -->
        <div class="view-mode">
            <p><strong>Username:</strong> <?php echo $username; ?></p>
            <p><strong>Email:</strong> <?php echo $email; ?></p>
            <p><strong>First Name:</strong> <?php echo $firstname; ?></p>
            <p><strong>Middle Name:</strong> <?php echo $middlename; ?></p>
            <p><strong>Last Name:</strong> <?php echo $lastname; ?></p>
            <p><strong>Birthday:</strong> <?php echo $birthday; ?></p>
            <p><strong>Contact Number:</strong> <?php echo $contactno; ?></p>
            <p><strong>Address:</strong>
                <?php
                // Fetch selected address
                $address_query = "SELECT * FROM addresses WHERE id = $address_id";
                $address_result = $conn->query($address_query);
                if ($address_result && $address_result->num_rows > 0) {
                    $address_row = $address_result->fetch_assoc();
                    echo $address_row['address'] . ', ' . $address_row['city'] . ', ' . $address_row['province'] . ', ' . $address_row['zip'];
                }
                ?>
            </p>
            <?php if ($userImage): ?>
                <img src="<?php echo $userImage; ?>" alt="User Image">
            <?php endif; ?>
            <button onclick="switchToEditMode()">Edit</button>
        </div>

        <!-- Edit Mode -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" class="edit-mode">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" required>

            <label for="middlename">Middle Name:</label>
            <input type="text" id="middlename" name="middlename" value="<?php echo $middlename; ?>">

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" required>

            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" value="<?php echo $birthday; ?>">

            <label for="contactno">Contact Number:</label>
            <input type="text" id="contactno" name="contactno" value="<?php echo $contactno; ?>" required>

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

            <div id="other_address" class="other-address">
                <div class="address-fields">
                    <label for="new_address">New Address:</label>
                    <input type="text" id="new_address" name="new_address">

                    <label for="new_city">City:</label>
                    <input type="text" id="new_city" name="new_city">

                    <label for="new_province">Province:</label>
                    <input type="text" id="new_province" name="new_province">

                    <label for="new_zip">ZIP:</label>
                    <input type="text" id="new_zip" name="new_zip">
                </div>
            </div>

            <label for="userImage">User Image:</label>
            <?php if ($userImage): ?>
                <img src="<?php echo $userImage; ?>" alt="User Image">
            <?php endif; ?>
            <input type="file" id="userImage" name="userImage">

            <button type="submit" name="update_user">Save Changes</button>
            <button type="button" onclick="switchToViewMode()">Cancel</button>
        </form>
    </div>

    <a href="user_dashboard.php">Back to Dashboard</a>

    <script>
        function switchToEditMode() {
            document.querySelector('.view-mode').style.display = 'none';
            document.querySelector('.edit-mode').style.display = 'block';
        }

        function switchToViewMode() {
            document.querySelector('.view-mode').style.display = 'block';
            document.querySelector('.edit-mode').style.display = 'none';
        }

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
