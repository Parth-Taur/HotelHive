<?php
session_start();
include 'db.php';  // Include database connection

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM listings WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: index.php');  // Redirect to home page after deletion
        exit();
    } else {
        echo "Error deleting listing.";
    }

    $stmt->close();
}
?>
