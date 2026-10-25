<?php
session_start();
include 'db.php';  // Include database connection
include 'navbar.php';  // Include navbar

$searchTerm = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? '';

// SQL query to fetch listings with search term and/or category filter
$sql = "
    SELECT id, title, description, price, location, category, image_url 
    FROM listings 
    WHERE (title LIKE ? OR description LIKE ? OR location LIKE ?)
";

$params = ["%$searchTerm%", "%$searchTerm%", "%$searchTerm%"];

if ($categoryFilter) {
    $sql .= " AND category = ?";
    $params[] = $categoryFilter;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HotelHive - Find Your Stay</title>
    <link rel="stylesheet" href="./css.css">
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        crossorigin="anonymous"
    >
    <style>
        /* Custom styling to align search bar and button */
        .search-container .form-control {
            height: 55px;
            border-radius: 30px 0 0 30px;
            border: solid black 0.1px;
            box-shadow: none;
        }

        .search-container .btn {
            height: 55px;
            border-radius: 0 30px 30px 0;
            background-color: #fe424d;
            color: white;
            border: none;
        }

        .search-container .btn:hover {
            background-color: #ff6666;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Find Your Perfect Stay</h1>

    <!-- Search Form -->
    <form method="GET" action="index.php" class="search-container mb-4">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Search by title, location, or description..." value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button class="btn" type="submit">Search</button>
        </div>
    </form>

    <!-- Category Filter -->
    <form method="GET" action="index.php" class="mb-4">
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <option value="Apartment" <?php echo $categoryFilter === 'Apartment' ? 'selected' : ''; ?>>Apartment</option>
                <option value="Villa" <?php echo $categoryFilter === 'Villa' ? 'selected' : ''; ?>>Villa</option>
                <option value="Farmhouse" <?php echo $categoryFilter === 'Farmhouse' ? 'selected' : ''; ?>>Farmhouse</option>
                <option value="Castle" <?php echo $categoryFilter === 'Castle' ? 'selected' : ''; ?>>Castle</option>
                <option value="Beachfront" <?php echo $categoryFilter === 'Beachfront' ? 'selected' : ''; ?>>Beachfront</option>
            </select>
        </div>
    </form>

    <!-- Listings Results -->
    <div class="row mt-4">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($listing = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars($listing['image_url']); ?>" alt="Listing Image" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($listing['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($listing['description']); ?></p>
                            <p class="text-muted"><strong>₹<?php echo $listing['price']; ?>/night</strong> - <?php echo htmlspecialchars($listing['location']); ?></p>
                            <a href="show_listing.php?id=<?php echo $listing['id']; ?>" class="btn btn-outline-danger w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                No listings found. Try another search or category.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>


<script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    crossorigin="anonymous">
</script>
</body>
</html>

