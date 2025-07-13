<?php
// DB config
$servername = "localhost";
$username = "root";
$dbname = "mydatabase";
$password = "";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$age = $_POST['age'];

// Prepare & insert
$stmt = $conn->prepare("INSERT INTO info (name, age) VALUES (?, ?)");
$stmt->bind_param("si", $name, $age);

if ($stmt->execute()) {
    echo "Data saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
