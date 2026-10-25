<?php
session_start();
include 'db.php';  // Include database connection

// Get the listing ID from the URL
$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $listing = $result->fetch_assoc();
    } else {
        echo "Listing not found.";
        exit();
    }

    $stmt->close();
} else {
    header('Location: index.php');  // Redirect to home if no ID is provided
    exit();
}

// Check if the user is the owner of the listing
$is_owner = (isset($_SESSION['username']) && $_SESSION['username'] === $listing['owner']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($listing['title']); ?> - HotelHive</title>
    <link rel="stylesheet" href="./css.css">
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous"
    >
</head>
<body>
    <?php include 'navbar.php'; ?>  <!-- Navbar included here -->

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo htmlspecialchars($listing['image_url']); ?>" class="img-fluid rounded" alt="Listing Image">
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($listing['title']); ?></h1>
                <p><?php echo htmlspecialchars($listing['description']); ?></p>
                <p><strong>Price:</strong> ₹<?php echo $listing['price']; ?> / night</p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($listing['location']); ?>, <?php echo htmlspecialchars($listing['country']); ?></p>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($listing['category']); ?></p>

                <a href="index.php" class="btn btn-secondary mt-3">Back</a>
                <a href="book.php?id=<?php echo $listing['id']; ?>" class="btn btn-danger mt-3">Book Now</a>

                <?php if ($is_owner): ?>
                    <a href="update_listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-warning mt-3">Update</a>
                    <a href="delete_listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-danger mt-3">Delete</a>
                <?php endif; ?>
            </div>
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

