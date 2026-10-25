<?php
session_start();
include 'db.php';  // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: signup.html');  // Redirect to signup if not logged in
    exit();
}

$username = $_SESSION['username'];
$listing_id = $_GET['id'] ?? null;
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Check if the selected dates are available
    $stmt = $conn->prepare(
        "SELECT * FROM bookings WHERE listing_id = ? 
        AND ((check_in <= ? AND check_out >= ?) OR (check_in <= ? AND check_out >= ?))"
    );
    $stmt->bind_param('issss', $listing_id, $check_out, $check_in, $check_in, $check_out);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error = "The selected dates are not available. Please choose different dates.";
    } else {
        // Insert the booking into the database
        $stmt = $conn->prepare(
            "INSERT INTO bookings (listing_id, username, check_in, check_out) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param('isss', $listing_id, $username, $check_in, $check_out);

        if ($stmt->execute()) {
            header('Location: index.php');  // Redirect to home page after booking
            exit();
        } else {
            $error = "Booking failed. Please try again.";
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Stay - HotelHive</title>
    <link rel="stylesheet" href="./css.css">
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        crossorigin="anonymous"
    >
</head>
<body>
    <!-- Include the reusable navbar -->
    <?php include 'navbar.php'; ?>

    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card p-4" style="width: 28rem;">
            <h3 class="text-center mb-4">Book Your Stay</h3>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="check_in" class="form-label">Check-in Date</label>
                    <input type="date" name="check_in" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="check_out" class="form-label">Check-out Date</label>
                    <input type="date" name="check_out" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger w-100 mt-2">Confirm Booking</button>
            </form>

            <div class="text-center mt-3">
                <a href="show_listing.php?id=<?php echo $listing_id; ?>" class="text-decoration-none">Back to Listing</a>
            </div>
        </div>
    </div>

    <footer class="footer mt-5">
        <div class="f-info">
            <div class="f-info-socials">
                <i class="fa-brands fa-square-facebook"></i>
                <i class="fa-brands fa-square-twitter"></i>
                <i class="fa-brands fa-square-instagram"></i>
            </div>
            <div class="f-info-brand">
                &copy; HotelHive Private Limited
            </div>
            <div class="f-info-links">
                <a href="./privacy.html">Privacy</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="./terms.html">Terms</a>
            </div>
        </div>
    </footer>

    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        crossorigin="anonymous">
    </script>
</body>
</html>
