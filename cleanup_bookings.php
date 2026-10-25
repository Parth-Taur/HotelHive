<?php
include 'db.php';  // Include database connection

// Delete bookings with a past check-out date
$sql = "DELETE FROM bookings WHERE check_out < CURDATE()";
if ($conn->query($sql) === TRUE) {
    echo "Expired bookings deleted successfully.";
} else {
    echo "Error deleting expired bookings: " . $conn->error;
}

$conn->close();
?>
