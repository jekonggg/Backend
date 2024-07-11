-- Create the database
CREATE DATABASE autosuphply;

-- Use the newly created database
USE autosuphply;

-- Create the Addresses table
CREATE TABLE addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    address VARCHAR(100) NOT NULL,
    city VARCHAR(50) NOT NULL,
    province VARCHAR(2) NOT NULL,
    zip VARCHAR(10) NOT NULL
);

-- Create the Users table
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    middlename VARCHAR(100),
    lastname VARCHAR(100) NOT NULL,
    birthday DATE,
    contactno VARCHAR(11) NOT NULL,
    address INT,
    userimage VARCHAR(255) NOT NULL,
    Role ENUM('Admin', 'User') NOT NULL,
    FOREIGN KEY (address) REFERENCES addresses(id)
);

-- Create the Category table
CREATE TABLE Category (
    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
    CategoryName VARCHAR(100) NOT NULL
);

-- Create the Brand table
CREATE TABLE Brand (
    BrandID INT AUTO_INCREMENT PRIMARY KEY,
    BrandName VARCHAR(100) NOT NULL
);

-- Create the Product table
CREATE TABLE Product (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL,
    Stock INT NOT NULL,
    CategoryID INT,
    BrandID INT,
    Quantity INT NOT NULL,
    Variation VARCHAR(100),
    ProductImages VARCHAR(500), NOT NULL
    CompatibleVehicle VARCHAR(100),
    FOREIGN KEY (CategoryID) REFERENCES Category(CategoryID),
    FOREIGN KEY (BrandID) REFERENCES Brand(BrandID)
);

-- Create the Orders table
CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    OrderDate DATETIME NOT NULL,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    Status ENUM('Shipped', 'Arrived', 'Cancelled') NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create the OrderItem table
CREATE TABLE OrderItem (
    OrderItemID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

-- Create the Review table
CREATE TABLE Review (
    ReviewID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    ProductID INT,
    Rating INT NOT NULL,
    Comment TEXT,
    ReviewDate DATETIME NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

-- Create the Cart table
CREATE TABLE Cart (
    CartID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Create the CartItem table
CREATE TABLE CartItem (
    CartItemID INT AUTO_INCREMENT PRIMARY KEY,
    CartID INT,
    ProductID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (CartID) REFERENCES Cart(CartID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

CREATE TABLE car_types (
    CarTypeID INT AUTO_INCREMENT PRIMARY KEY,
    CarType VARCHAR(100) NOT NULL
);

-- Create the ProductCompatibleVehicles table
CREATE TABLE ProductCompatibleVehicles (
    ProductID INT,
    CarTypeID INT,
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID),
    FOREIGN KEY (CarTypeID) REFERENCES car_types(CarTypeID),
    PRIMARY KEY (ProductID, CarTypeID)
);