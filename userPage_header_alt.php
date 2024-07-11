<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <style>
        .header {
            width: 100%;
            height: 120px;
            background: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative; /* Removed absolute positioning */
        }

        .search-container {
            position: relative;
            width: 900px;
            height: 50px;
            margin-right: 450px;
        }

        .profile-img, .cart-img {
            margin-right: 25px;
            height: 40px;
            width: auto;
        }

        .cart-button {
            position: absolute;
            right: 110px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            padding: 0;
            cursor: pointer;
            background: none;
        }

        .profile-button {
            position: absolute;
            right: 50px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            padding: 0;
            cursor: pointer;
        }

        .logo {
            margin-left: 200px;
            margin-top: 20px;
            height: 50px;
            width: auto;
        }

        .logo-container {
            flex: 1;
        }

        .header-line {
            border: none; /* Remove default border */
            border-top: 1px solid #EE2222; /* Custom border style */
            margin: 0; /* Remove default margin */
            width: 100%; /* Full width */
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <a href="userPage_homePage.php"><img src="Repositories/logo-mini.png" class="logo" alt="logo"></a>
        </div>
        <button class="cart-button"><img src="Repositories/cart.png" alt="cart" class="cart-img"></button>
        <button class="profile-button"><img src="Repositories/profile.png" alt="profile" class="profile-img"></button>
    </div>

    <hr class="header-line">
</body>
</html>
