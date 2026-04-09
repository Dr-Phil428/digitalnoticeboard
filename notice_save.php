<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $postingDate = $_POST['posting_date'];
    $expiryDate = $_POST['expiry_date'];
    $authorId = 1; // Example: logged-in admin ID

    // Always save as pending
    $status = 'pending';

    $stmt = $conn->prepare("INSERT INTO notices_save 
        (title, content, category, priority, posting_date, expiry_date, status, author_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $title, $content, $category, $priority, $postingDate, $expiryDate, $status, $authorId);

    if ($stmt->execute()) {
        echo "Notice saved successfully as pending!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>