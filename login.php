<?php
session_start();
include 'db.php'; // your DB connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId   = $_POST['user_id'];   // from login form
    $password = $_POST['password'];

    // Query user by userId
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password (hashed with password_hash at signup)
        if (password_verify($password, $user['password'])) {
            // Store session data
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name']    = $user['first_name'] . " " . $user['last_name'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: Admin.html");
            } elseif ($user['role'] === 'staff') {
                header("Location: Staff/staff.php");
            } else {
                header("Location: LOGIN1.html?error=unauthorized");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found.";
    }
}
?>