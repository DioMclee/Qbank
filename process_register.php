<?php
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
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];
$gender = $_POST['gender'];

// Prepare and execute the query
$sql = "INSERT INTO users (first_name, last_name, email, username, password, gender) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $first_name, $last_name, $email, $username, $password, $gender);

if ($stmt->execute()) {
    // Successful registration
    header("Location: login.php");
} else {
    // Failed registration
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
