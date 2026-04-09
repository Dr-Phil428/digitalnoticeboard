<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../db.php'; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId   = $_SESSION['user_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email    = $_POST['email'];

    // Adjust table/column names to match your DB schema
    $stmt = $conn->prepare("UPDATE users SET first_name=?, last_name=?, email=?  WHERE user_id=?");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $firstName, $lastName, $email, $userId);

    if ($stmt->execute()) {
        $_SESSION['first_name']  = $firstName;
        $_SESSION['last_name']  = $lastName;
        $_SESSION['email'] = $email;
        header("Location: profile.php?update=success");
        exit();
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>