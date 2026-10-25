<?php
session_start();
include 'db.php';  // Include database connection

$id = $_GET['id'] ?? null;
$error = '';

// Fetch the existing listing details
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
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $country = $_POST['country'];
    $location = $_POST['location'];
    $category = $_POST['category'];

    $stmt = $conn->prepare(
        "UPDATE listings SET title = ?, description = ?, price = ?, country = ?, location = ?, category = ? WHERE id = ?"
    );
    $stmt->bind_param('ssisssi', $title, $description, $price, $country, $location, $category, $id);

    if ($stmt->execute()) {
        header('Location: show_listing.php?id=' . $id);  // Redirect to the listing page after update
        exit();
    } else {
        $error = "Error updating listing.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Listing - HotelHive</title>
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

    <div class="container mt-5">
        <h3 class="mb-4">Update Your Property</h3>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Listing Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($listing['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($listing['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price per Night (₹)</label>
                <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($listing['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" name="country" class="form-control" value="<?php echo htmlspecialchars($listing['country']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($listing['location']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($listing['category']); ?>" required>
            </div>

            <button type="submit" class="btn btn-danger">Update Listing</button>
            <a href="show_listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-secondary ms-2">Cancel</a>
        </form>
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
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous">
    </script>
</body>
</html>
