<?php
include 'db.php';

$id = $_POST['id'];
$sql = "UPDATE reminders SET reminder_time = ADDTIME(reminder_time, '00:15:00') WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

echo "Snoozed!";
?>