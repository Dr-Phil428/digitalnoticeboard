<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $user_id    = $_POST['user_id'];
    $email      = $_POST['email'];
    $password   = $_POST['password'];
    $confirm    = $_POST['confirm_password'];

    // Check password match
    if ($password !== $confirm) {
        die("Passwords do not match.");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Determine role based on user_id
    if ($user_id === 'AN001') {
        $role = 'admin';
    } elseif (strpos($user_id, 'SN') === 0) {
        $role = 'staff';
    } else {
        die("Invalid UserID format. Only AN001 or SN... allowed.");
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO users (user_id, first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $user_id, $first_name, $last_name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        // Redirect back to login page
        header("Location: LOGIN1.html?signup=success");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>