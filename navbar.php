<?php
// Ensure session is started only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();  // Start session if not already started
}

include 'db.php';  // Include database connection

// Check if the user is logged in
$username = $_SESSION['username'] ?? '';

// Initialize ownership and booking variables
$has_listings = false;

// Check if the logged-in user owns any listings
if ($username) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS listing_count FROM listings WHERE owner = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $has_listings = $row['listing_count'] > 0;
    $stmt->close();
}
?>

<nav class="navbar navbar-expand-md bg-body-light border-bottom sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><i class="fa-regular fa-compass"></i> HotelHive</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ms-auto">

                <!-- Register Property Link -->
                <a class="nav-link ms-3" href="new.php">Register Your Property</a>

                <!-- Conditional My Listings Link (for owners) -->
                <?php if ($has_listings): ?>
                    <a class="nav-link ms-3" href="my_listings.php">My Listings</a>
                <?php endif; ?>

                <?php if ($has_listings): ?>
                    <a class="nav-link ms-3" href="new_bookings.php">New Bookings</a>
                <?php endif; ?>

                <!-- Conditional My Bookings Link -->
                <?php if ($username): ?>
                    <a class="nav-link ms-3" href="my_bookings.php">My Bookings</a>
                <?php endif; ?>

                <!-- Login/Logout Options -->
                <?php if ($username): ?>
                    <a class="nav-link ms-3" href="logout.php">Logout</a>
                    <a class="nav-link ms-3" href="delete_account.html">Delete Account</a>
                <?php else: ?>
                    <a class="nav-link ms-3" href="signup.html">Signup / Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
