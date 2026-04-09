<?php
session_start();
include 'db.php'; // this brings in $conn

$role = $_POST['role'];
$user_id = $_POST['user_id'];
$password = $_POST['password'];

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=? AND role=?");
$stmt->bind_param("ss", $user_id, $role);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // Credentials are valid
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];

    // Redirect all valid users to the same login page
    header("Location: LOGIN1.html");
    exit;
} else {
    // Invalid credentials
    echo "<script>alert('Invalid credentials'); window.location='checkAccess.html';</script>";
}
?>