<?php
session_start();
include 'db.php';  // Include database connection
require 'cloudinary.php';  // Include Cloudinary configuration

use Cloudinary\Api\Upload\UploadApi;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $country = $_POST['country'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $owner = $_SESSION['username'];  // Assume owner is the logged-in user

    try {
        // Upload the image to Cloudinary
        $uploadedImage = (new UploadApi())->upload($_FILES['image']['tmp_name'], [
            'folder' => 'hotelhive/listings'
        ]);

        $imageUrl = $uploadedImage['secure_url'];  // Get the image URL

        // Insert the new listing into the database
        $stmt = $conn->prepare(
            "INSERT INTO listings (title, description, price, country, location, category, owner, image_url) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('ssisssss', $title, $description, $price, $country, $location, $category, $owner, $imageUrl);

        if ($stmt->execute()) {
            header('Location: index.php');  // Redirect to the home page
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } catch (Exception $e) {
        echo "Image upload failed: " . $e->getMessage();
    }
}
?>
