<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId      = $_POST['user_id']; // make sure your form sends this
    $newPassword = $_POST['newPassword'];
    $confirm     = $_POST['confirmPassword'];

    if ($newPassword !== $confirm) {
        die("Passwords do not match.");
    }

    $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password=? WHERE user_id=?");
    $stmt->bind_param("ss", $hashed_password, $userId);

    if ($stmt->execute()) {
    echo "<p style='color:green;'>Password reset successful. Redirecting to login...</p>";
    header("Refresh:3; url=LOGIN1.html"); // wait 3 seconds, then redirect
    exit();

    } else {
        echo "Error updating password: " . $stmt->error;
    }
}
?>