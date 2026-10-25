<?php
session_start();
include 'db.php';  

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['listing']['title'];
    $description = $_POST['listing']['description'];
    $price = $_POST['listing']['price'];
    $country = $_POST['listing']['country'];
    $location = $_POST['listing']['location'];
    $category = $_POST['listing']['category'];
    $owner = $_SESSION['username'];

    $stmt = $conn->prepare(
        "INSERT INTO listings (title, description, price, country, location, category, owner) 
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param(
        'ssissss', 
        $title, $description, $price, $country, $location, $category, $owner
    );

    if ($stmt->execute()) {
        header('Location: index.php'); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
