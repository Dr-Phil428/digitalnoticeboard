<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $grade    = $_POST['grade'];
    $email    = $_POST['email'];
    $category = $_POST['category'];
    $message  = $_POST['message'];

    $sql = "INSERT INTO parent_feedback (grade, email, category, message) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $grade, $email, $category, $message);

    if ($stmt->execute()) {
        echo "Parent feedback submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>