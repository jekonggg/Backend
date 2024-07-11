<?php
// Start the session
session_start();

// Database connection details
include('dbconnection.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for form data
$username = $email = $firstname = $middlename = $lastname = $birthday = $contactno = $address = $city = $province = $zip = "";
$username_err = $email_err = $password_err = $confirmpassword_err = $image_err = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $username = $conn->real_escape_string(trim($_POST["username"]));
    $email = $conn->real_escape_string(trim($_POST["email"]));
    $firstname = $conn->real_escape_string(trim($_POST["firstname"]));
    $middlename = $conn->real_escape_string(trim($_POST["middlename"]));
    $lastname = $conn->real_escape_string(trim($_POST["lastname"]));
    $birthday = $conn->real_escape_string(trim($_POST["birthday"]));
    $contactno = $conn->real_escape_string(trim($_POST["contactno"]));
    $address = $conn->real_escape_string(trim($_POST["address"]));
    $city = $conn->real_escape_string(trim($_POST["city"]));
    $province = $conn->real_escape_string(trim($_POST["province"]));
    $zip = $conn->real_escape_string(trim($_POST["zip"]));

    // Validate password
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    if ($password !== $confirmpassword) {
        $password_err = "Passwords do not match.";
    }

    // Handle image upload
    if (isset($_FILES['userImage'])) {
        $userImage = $_FILES['userImage'];
        $imageName = $userImage['name'];
        $imageTmpName = $userImage['tmp_name'];
        $imageSize = $userImage['size'];
        $imageError = $userImage['error'];

        $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
        $allowedExtensions = array('jpg', 'jpeg', 'png');

        if (in_array(strtolower($imageExtension), $allowedExtensions)) {
            if ($imageError === 0) {
                if ($imageSize <= 2 * 1024 * 1024) { // 2MB limit
                    $imageDestination = 'uploads/userimages/' . $imageName;
                    move_uploaded_file($imageTmpName, $imageDestination);
                } else {
                    $image_err = "Error: Image file size exceeds 2MB limit.";
                }
            } else {
                $image_err = "Error uploading the image.";
            }
        } else {
            $image_err = "Error: Only JPG, JPEG, and PNG files are allowed.";
        }
    }

    // If no errors, proceed with registration
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($image_err)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the address into the Addresses table
        $address_sql = "INSERT INTO addresses (address, city, province, zip) VALUES ('$address', '$city', '$province', '$zip')";

        if ($conn->query($address_sql) === TRUE) {
            $address_id = $conn->insert_id; // Get the ID of the inserted address

            // Insert the user into the Users table with the image path
            $user_sql = "INSERT INTO Users (Username, Password, Email, firstname, middlename, lastname, birthday, contactno, address, Role, UserImage)
                         VALUES ('$username', '$hashed_password', '$email', '$firstname', '$middlename', '$lastname', '$birthday', '$contactno', '$address_id', 'User', '$imageDestination')";

            if ($conn->query($user_sql) === TRUE) {
                echo "Registration successful!";
                // Redirect the user to the login page or display a success message
                header("Location: login.php");
                exit();
            } else {
                echo "Error: " . $user_sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $address_sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
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
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="password"], input[type="email"], input[type="date"], input[type="file"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register Account</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirmpassword" required>
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
                <label>First Name:</label>
                <input type="text" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
            </div>
            <div class="form-group">
                <label>Middle Name:</label>
                <input type="text" name="middlename" value="<?php echo htmlspecialchars($middlename); ?>">
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <input type="text" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
            </div>
            <div class="form-group">
                <label>Birthday:</label>
                <input type="date" name="birthday" value="<?php echo htmlspecialchars($birthday); ?>" required>
            </div>
            <div class="form-group">
                <label>Contact No.:</label>
                <input type="text" name="contactno" value="<?php echo htmlspecialchars($contactno); ?>" required>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            </div>
            <div class="form-group">
                <label>City:</label>
                <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
            </div>
            <div class="form-group">
                <label>Province:</label>
                <input type="text" name="province" value="<?php echo htmlspecialchars($province); ?>" required>
            </div>
            <div class="form-group">
                <label>Zip Code:</label>
                <input type="text" name="zip" value="<?php echo htmlspecialchars($zip); ?>" required>
            </div>
            <div class="form-group">
                <label>User Image:</label>
                <input type="file" name="userImage" accept="image/jpeg, image/png" required>
                <span class="error"><?php echo $image_err; ?></span>
            </div>
            <input type="submit" name="submit" value="Register">
        </form>
    </div>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
