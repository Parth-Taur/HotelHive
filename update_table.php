<?php
include 'db.php';  // Include database connection

// Add 'image_url' column to the 'listings' table
$sql = "ALTER TABLE listings ADD COLUMN image_url VARCHAR(255) NOT NULL";

if ($conn->query($sql) === TRUE) {
    echo "Table 'listings' updated successfully!";
} else {
    echo "Error updating table: " . $conn->error;
}

$conn->close();
?>
