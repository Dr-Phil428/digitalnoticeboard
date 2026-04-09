<?php
// Detect environment: localhost vs InfinityFree
$hostName = $_SERVER['HTTP_HOST'];

if ($hostName === 'localhost' || $hostName === '127.0.0.1') {
    // Localhost credentials
    $host = "localhost";
    $user = "root";
    $pass = "";              // usually empty for XAMPP/WAMP
    $db   = "schoolnb";      // your local database name
} else {
    // InfinityFree credentials (from your cPanel)
    $host = "sql303.infinityfree.com";       // MySQL hostname
    $user = "if0_41511990";                  // MySQL username
    $pass = "Macharia341";                   // MySQL password
    $db   = "if0_41511990_schoolnb";         // MySQL database name
}

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>