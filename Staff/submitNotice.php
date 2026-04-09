<?php
include '../db.php';

$title       = $_POST['title'];
$content     = $_POST['content'];
$category    = $_POST['category'];
$priority    = $_POST['priority'];
$postingDate = $_POST['posting_date'];
$expiryDate  = $_POST['expiry_date'];
$attachment  = $_FILES['attachment']['name'] ?? null;

if ($attachment) {
    $target = "uploads/" . basename($attachment);
    move_uploaded_file($_FILES['attachment']['tmp_name'], $target);
}

$sql = "INSERT INTO staff_notices 
        (title, content, category, priority, posting_date, expiry_date, attachment, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$status = "pending"; // 8th parameter

$stmt->bind_param("ssssssss", 
    $title, $content, $category, $priority, $postingDate, $expiryDate, $attachment, $status
);

$stmt->execute();

echo "Notice submitted successfully. Pending admin approval.";
?>