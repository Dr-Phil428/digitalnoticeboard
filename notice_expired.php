<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "schoolnb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$title       = trim($_POST['title'] ?? '');
$content     = trim($_POST['content'] ?? '');
$category    = trim($_POST['category'] ?? '');
$priority    = trim($_POST['priority'] ?? '');
$postingDate = trim($_POST['posting_date'] ?? '');
$expiryDate  = trim($_POST['expiry_date'] ?? '');
$authorId    = 1; // replace with $_SESSION['user_id'] if login system exists

// Handle attachment (optional)
$attachment = null;
if (!empty($_FILES['attachment']['name'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $attachment = $targetDir . time() . "_" . basename($_FILES["attachment"]["name"]);
    if (!move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachment)) {
        die("Error uploading file.");
    }
}

// Insert into DB with status = expired
$sql = "INSERT INTO notices_post
        (`Title`, `content`, `category`, `Priority`, `posting date`, `expiry date`, `attachment`, `status`, `author_id`, `created_at`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'archived', ?, NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param(
    "sssssssi",
    $title,
    $content,
    $category,
    $priority,
    $postingDate,
    $expiryDate,
    $attachment,
    $authorId
);

if ($stmt->execute()) {
    echo "Notice archived as expired successfully!";
} else {
    echo "Insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
