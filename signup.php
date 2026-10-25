<?php
// Include the database connection file
include 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $username = $_POST['user']['username'];
    $email = $_POST['user']['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);  // Secure the password

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        // Redirect to login page on success
        header('Location: login.html');
        exit();
    } else {
        // Show error message if something goes wrong
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
