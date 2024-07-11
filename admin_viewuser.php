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

// Function to fetch all users from the database
function fetchUsers($conn) {
    $sql = "SELECT * FROM Users";
    $result = $conn->query($sql);
    return $result;
}

// Function to display user data
function displayUsers($conn, $result) {
    if ($result->num_rows > 0) {
        echo "<div class='table-container'>";
        echo "<table>";
        echo "<tr><th>User ID</th><th>Username</th><th>Email</th><th>First Name</th><th>Last Name</th><th>Birthday</th><th>Contact Number</th><th>Address</th><th>Role</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row['UserID']."</td>";
            echo "<td>".$row['Username']."</td>";
            echo "<td>".$row['Email']."</td>";
            echo "<td>".$row['firstname']."</td>";
            echo "<td>".$row['lastname']."</td>";
            echo "<td>".$row['birthday']."</td>";
            echo "<td>".$row['contactno']."</td>";

            // Fetch address details
            $address_id = $row['address'];
            $address_query = "SELECT * FROM addresses WHERE id = $address_id";
            $address_result = $conn->query($address_query);
            if ($address_result && $address_result->num_rows > 0) {
                $address_row = $address_result->fetch_assoc();
                $full_address = $address_row['address'] . ', ' . $address_row['city'] . ', ' . $address_row['province'] . ', ' . $address_row['zip'];
                echo "<td>".$full_address."</td>";
            } else {
                echo "<td>Address not found</td>";
            }

            echo "<td>".$row['Role']."</td>";
            echo "<td>";
            echo "<a href='admin_edituser.php?id=".$row['UserID']."'>Edit</a> | ";
            echo "<a href='admin_deleteuser.php?id=".$row['UserID']."' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a> | ";
            // Add edit role links based on current role
            if ($row['Role'] === 'User') {
                echo "<a href='admin_editrole.php?id=".$row['UserID']."&role=Admin'>Promote to Admin</a>";
            } elseif ($row['Role'] === 'Admin') {
                echo "<a href='admin_editrole.php?id=".$row['UserID']."&role=User'>Demote to User</a>";
            }
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p>No users found.</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Users</title>
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
        .add-user-btn {
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
        .add-user-btn:hover {
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
    <h1>Admin - View Users</h1>

    <!-- Logout Button -->
    <form action="logout.php" method="post">
        <button type="submit" name="logout">Logout</button>
    </form>

    <!-- Add New User Button -->
    <a href="admin_adduser.php" class="add-user-btn">Add New User</a>

    <div class="container">
        <?php
        $users = fetchUsers($conn);
        displayUsers($conn, $users);
        ?>
    </div>

    <a href="admin_dashboard.php">Home</a>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
