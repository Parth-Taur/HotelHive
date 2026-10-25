<?php
include 'db.php';  // Include your database connection

// Drop the bookings table if it exists
$conn->query("DROP TABLE IF EXISTS bookings");

// Create the users table
$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully.<br>";
} else {
    echo "Error creating 'users' table: " . $conn->error . "<br>";
}

// Create the listings table
$sql = "
CREATE TABLE IF NOT EXISTS listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price INT NOT NULL,
    country VARCHAR(100),
    location VARCHAR(100),
    category VARCHAR(100),
    owner VARCHAR(100) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner) REFERENCES users(username) ON DELETE CASCADE
) ENGINE=InnoDB;
";

if ($conn->query($sql) === TRUE) {
    echo "Table 'listings' created successfully.<br>";
} else {
    echo "Error creating 'listings' table: " . $conn->error . "<br>";
}

// Create the bookings table
$sql = "
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    username VARCHAR(100) NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
    FOREIGN KEY (username) REFERENCES users(username) ON DELETE CASCADE
) ENGINE=InnoDB;
";

if ($conn->query($sql) === TRUE) {
    echo "Table 'bookings' created successfully!";
} else {
    echo "Error creating 'bookings' table: " . $conn->error;
}

// Close the connection
$conn->close();
?>

