<?php
session_start();
include 'db.php';  // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: signup.html');  // Redirect to signup if not logged in
    exit();
}

$username = $_SESSION['username'];

// Fetch bookings made by the logged-in user
$sql = "
    SELECT bookings.id AS booking_id, listings.title AS listing_title, 
           bookings.check_in, bookings.check_out, listings.owner
    FROM bookings 
    JOIN listings ON bookings.listing_id = listings.id
    WHERE bookings.username = ?
    ORDER BY bookings.check_in DESC;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - HotelHive</title>
    <link rel="stylesheet" href="./css.css">
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous"
    >
</head>
<body>
    <nav class="navbar navbar-expand-md bg-body-light border-bottom sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><i class="fa-regular fa-compass"></i> HotelHive</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card p-4" style="width: 40rem;">
            <h3 class="text-center mb-4">My Bookings</h3>

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Listing Title</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Owner</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $booking['booking_id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['listing_title']); ?></td>
                                <td><?php echo $booking['check_in']; ?></td>
                                <td><?php echo $booking['check_out']; ?></td>
                                <td><?php echo htmlspecialchars($booking['owner']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    No bookings found.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>


    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous">
    </script>
</body>
</html>
