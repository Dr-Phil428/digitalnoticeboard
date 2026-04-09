<?php
include 'db.php';

$title = $_POST['title'];
$date = $_POST['date'];
$time = $_POST['time'];
$user_id = 1; // Example: replace with logged-in user ID

$sql = "INSERT INTO reminders (user_id, title, reminder_date, reminder_time) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $user_id, $title, $date, $time);
$stmt->execute();

echo json_encode([
  "id" => $stmt->insert_id,
  "title" => $title,
  "reminder_date" => $date,
  "reminder_time" => $time
]);
?>