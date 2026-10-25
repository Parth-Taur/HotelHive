<?php
session_start();
include 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    die("You must be logged in to delete your account.");
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username']; // Get the logged-in user's username
    $password = $_POST['password']; // Get the entered password

    // Verify the entered password
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password, $hashed_password)) {
        // Password matches, proceed to delete the account
        $delete_sql = "DELETE FROM users WHERE username = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("s", $username);

        if ($delete_stmt->execute()) {
            // Logout the user by destroying the session
            session_unset();
            session_destroy();

            echo "Your account has been deleted successfully.";
        } else {
            echo "Error deleting your account: " . $conn->error;
        }

        $delete_stmt->close();
    } else {
        echo "Incorrect password. Account deletion failed.";
    }
}

// Close the connection
$conn->close();
?>
