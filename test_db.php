<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "schoolnb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check database casing and spelling for expired posts
$sql = "SELECT * FROM notices_post";
$result = $conn->query($sql);

echo "Total notices: " . $result->num_rows . "\n";
while($row = $result->fetch_assoc()) {
    echo "ID: " . $row['Id'] . " | Title: " . $row['Title'] . " | Status: '" . $row['status'] . "'\n";
}

$conn->close();
?>