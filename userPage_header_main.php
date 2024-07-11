<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <style>
        /* Existing styles remain the same */
        .header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 120px;
            background: #EE2222;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .search-container {
            position: relative;
            width: 900px;
            height: 50px;
            margin-right: 450px;
        }

        .search {
            width: 100%;
            height: 100%;
            border-radius: 10px;
            background: #fff;
            border: none; 
            padding: 0 60px 0 20px;
            font-size: 16px;
            outline: none;
            box-sizing: border-box;
        }

        .search::placeholder {
            color: #aaa;
        }

        .search-button {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            border: none;
            background: none;
            cursor: pointer;
        }

        .search-img {
            height: 30px;
            width: 30px;
        }
        .profile-img, .cart-img {
            margin-right:25px;
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

        .logo{
            margin-left: 30px;
            margin-top: 20px;
            height: 130px;
            width: auto;
        }

        .logo-container {
            flex: 1;
        }

    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <a href="userPage_homePage.php"><img src="Repositories/logo-alt.png" class="logo" alt="logo"></a> 
        </div>
        <div class="search-container">
            <input type="text" class="search" placeholder="Search for a product">
            <button class="search-button"><img src="Repositories/search.png" alt="search" class="search-img"></button>
        </div>
        <button class="cart-button" id="cartToggle"><img src="Repositories/cart.png" alt="cart" class="cart-img"></button>
        <button class="profile-button"><img src="Repositories/profile.png" alt="profile" class="profile-img"></button>
    </div>
</body>
</html>
