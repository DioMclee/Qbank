<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "new-qbank";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute the query
$sql = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Successful login
    $_SESSION['username'] = $username;
    header("Location: root_page.html");
} else {
    // Failed login
    echo "<script>alert('Invalid username or password'); window.location.href='login.php';</script>";
}

$stmt->close();
$conn->close();
?>
