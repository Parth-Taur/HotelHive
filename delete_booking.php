<?php
session_start();
include 'db.php';  // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: signup.html');  // Redirect to signup if not logged in
    exit();
}

$booking_id = $_GET['id'] ?? null;

if ($booking_id) {
    // Delete the booking if it belongs to the logged-in user
    $stmt = $conn->prepare(
        "DELETE FROM bookings WHERE id = ? AND username = ? AND check_in > CURDATE()"
    );
    $stmt->bind_param('is', $booking_id, $_SESSION['username']);

    if ($stmt->execute()) {
        header('Location: my_bookings.php');  // Redirect to My Bookings page
        exit();
    } else {
        echo "Error deleting booking.";
    }

    $stmt->close();
} else {
    header('Location: my_bookings.php');  // Redirect if no booking ID is provided
    exit();
}

$conn->close();
?>
