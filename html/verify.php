<?php
session_start();

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection parameters
    $servername = "localhost";
    $username = "your_username";
    $password = "your_password";
    $dbname = "your_database_name";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize inputs
    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variable or redirect to dashboard
            $_SESSION['isLoggedIn'] = true;
            // Redirect to dashboard or any other page
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            // You can redirect back to the login page with an error message
            header("Location: index.php?error=invalid_credentials");
            exit();
        }
    } else {
        // User not found
        // You can redirect back to the login page with an error message
        header("Location: index.php?error=user_not_found");
        exit();
    }

    $conn->close();
}
?>
