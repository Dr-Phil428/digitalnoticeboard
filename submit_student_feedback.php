<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $grade      = $_POST['grade'];
    $category   = $_POST['category'];
    $message    = $_POST['message'];

    $sql = "INSERT INTO student_feedback (student_id, grade, category, message) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $student_id, $grade, $category, $message);

    if ($stmt->execute()) {
        echo "Student feedback submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>