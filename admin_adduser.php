<?php
session_start();
require_once('dbconnection.php');

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Initialize database connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to add a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthday = $_POST['birthday'];
    $contactno = $_POST['contactno'];
    $role = $_POST['role'];

    // Insert address into the addresses table
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);
    $province = $conn->real_escape_string($_POST['province']);
    $zip = $conn->real_escape_string($_POST['zip']);
    
    $insert_address_sql = "INSERT INTO addresses (address, city, province, zip) VALUES ('$address', '$city', '$province', '$zip')";
    if ($conn->query($insert_address_sql) === TRUE) {
        $address_id = $conn->insert_id;

        // Insert new user into the Users table
        $insert_user_sql = "INSERT INTO Users (Username, Email, Password, firstname, lastname, birthday, contactno, Role, address)
                            VALUES ('$username', '$email', '$password', '$firstname', '$lastname', '$birthday', '$contactno', '$role', '$address_id')";
        
        if ($conn->query($insert_user_sql) === TRUE) {
            echo "<script>alert('New user added successfully');</script>";
            // Redirect to avoid form resubmission on refresh
            header("Location: admin_viewuser.php");
            exit();
        } else {
            echo "Error: " . $insert_user_sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $insert_address_sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <h1>Add User</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        Username: <input type="text" name="username" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        First Name: <input type="text" name="firstname" required><br><br>
        Last Name: <input type="text" name="lastname" required><br><br>
        Birthday: <input type="date" name="birthday" required><br><br>
        Contact Number: <input type="text" name="contactno" required><br><br>
        Role:
        <select name="role">
            <option value="User">User</option>
            <option value="Admin">Admin</option>
        </select><br><br>
        Address:<br>
        Street Address: <input type="text" name="address" required><br>
        City: <input type="text" name="city" required><br>
        Province: <input type="text" name="province" required><br>
        ZIP Code: <input type="text" name="zip" required><br><br>
        <button type="submit" name="add_user">Add User</button>
    </form>
    <br>
    <a href="admin_viewuser.php">Back to Users</a>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
