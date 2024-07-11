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

// Check if the form is submitted
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the submitted username and password
    $submitted_username = $_POST["username"];
    $submitted_password = $_POST["password"];

    // Prepare the SQL statement to fetch the user
    $stmt = $conn->prepare("SELECT * FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $submitted_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["Password"];

        // Verify the password
        if (password_verify($submitted_password, $hashed_password)) {
            // Login successful
            $_SESSION["user_id"] = $row["UserID"]; // Save UserID in session
            $_SESSION["username"] = $row["Username"];
            $_SESSION["role"] = $row["Role"];
            $_SESSION["is_admin"] = $row["Role"] == "Admin"; // Set is_admin session variable

            // Redirect the user to the appropriate page based on their role
            if ($row["Role"] == "Admin") {
                header("Location: adminPage_Dashboard.php");
            } else {
                header("Location: user_dashboard.php");
            }
            exit;
        } else {
            // Incorrect password
            $error_message = "Invalid username or password.";
        }
    } else {
        // User not found
        $error_message = "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ee2222;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
        }
        .carousel-container {
            width: 100%;
            max-width: 800px;
            margin-bottom: 20px;
        }
        .carousel-container img {
            width: 100%;
            border-radius: 8px;
        }
        .main-container {
            display: flex;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            flex-direction: column;
            margin-top: 250px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }
        .header a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
        .header a.admin-login {
            text-decoration: underline;
        }
        .content {
            display: flex;
        }
        .container {
            width: 300px;
        }
        .container h2 {
            margin-bottom: 20px;
        }
        .input-container {
            margin-bottom: 15px;
        }
        .input-container label {
            display: block;
            margin-bottom: 5px;
        }
        .input-container input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .button-container {
            margin-bottom: 15px;
        }
        .button-container button {
            width: 100%;
            padding: 10px;
            background-color: #ee2222; /* Match the background color */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-container button:hover {
            background-color: #c00; /* Darker shade for hover */
        }

        .text-container {
            margin-top: 20px;
        }
        .text-container a {
            color: #4CAF50;
            text-decoration: none;
        }
        .text-container a:hover {
            text-decoration: underline;
        }
        .separator {
            width: 1px;
            background-color: #ddd;
            margin: 0 20px;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 300px;
            margin-bottom: 20px;
        }
        .logo-container img {
            max-width: 100%;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
        </div>
        <div class="content">
            <div class="logo-container">
                <img src="Repositories/logo.png" alt="Logo">
            </div>
            <div class="separator"></div>
            <div class="container">
                <h2>Sign In</h2>
                <?php if (!empty($error_message)) { ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php } ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="input-container">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="input-container">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="button-container">
                        <button type="submit" name="submit">Sign In</button>
                    </div>
                </form>
                <div class="text-container">
                    <p>No user account? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
