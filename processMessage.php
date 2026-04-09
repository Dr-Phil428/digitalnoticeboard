<?php
include 'db.php';

$recipient = $_POST['recipient']; // Staff ID
$category  = $_POST['category'];
$message   = $_POST['message'];

// Insert into database
$sql = "INSERT INTO messages (sender, recipient, category, message) 
        VALUES ('admin', '$recipient', '$category', '$message')";

if (mysqli_query($conn, $sql)) {
    // Redirect back to Admin dashboard after success
    header("Location: Admin.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>