<?php
session_start();
include 'db.php';  // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: signup.html');  // Redirect to signup if not logged in
    exit();
}

$username = $_SESSION['username'];

// Fetch all listings owned by the current user
$sql = "
    SELECT id, title 
    FROM listings 
    WHERE owner = ?
    ORDER BY created_at DESC;
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
    <title>My Listings - HotelHive</title>
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-nav ms-auto">
                <a class="nav-link ms-3" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="card p-4" style="width: 40rem;">
            <h3 class="text-center mb-4">My Listings</h3>

            <?php if ($result->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Listing ID</th>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($listing = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $listing['id']; ?></td>
                                <td><?php echo htmlspecialchars($listing['title']); ?></td>
                                <td>
                                    <a href="show_listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning text-center">
                    No listings found. <a href="new.php">Register a new property</a>.
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
