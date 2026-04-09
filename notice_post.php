<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "schoolnb");
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Collect form data safely
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
        echo json_encode(["status" => "error", "message" => "Error uploading file."]);
        exit;
    }
}

// Determine initial status (pending or active)
$today = date('Y-m-d');
$status = ($postingDate <= $today) ? 'active' : 'pending';

// Insert into DB
$sql = "INSERT INTO notices_post
        (`Title`, `content`, `category`, `Priority`, `posting_date`, `expiry_date`, `attachment`, `status`, `author_id`, `created_at`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param(
    "ssssssssi",
    $title,
    $content,
    $category,
    $priority,
    $postingDate,
    $expiryDate,
    $attachment,
    $status,
    $authorId
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Notice posted successfully",
        "notice" => [
            "title" => $title,
            "content" => $content,
            "category" => $category,
            "priority" => $priority,
            "posting_date" => $postingDate,
            "expiry_date" => $expiryDate,
            "attachment" => $attachment,
            "status" => $status
        ]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Insert failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>