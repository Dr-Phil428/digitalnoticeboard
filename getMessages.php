<?php
include 'db.php';

$messages = [];

// Student feedback
$sql = "SELECT student_id, grade, category, message, created_at 
        FROM student_feedback ORDER BY created_at DESC";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    $row['type'] = 'student';
    $messages[] = $row;
}

// Parent feedback
$sql = "SELECT grade, category, message, email, created_at 
        FROM parent_feedback ORDER BY created_at DESC";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    $row['type'] = 'parent';
    $messages[] = $row;
}

// Sort combined array by created_at (latest first)
usort($messages, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

echo json_encode($messages);
?>