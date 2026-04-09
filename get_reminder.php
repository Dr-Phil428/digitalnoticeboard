<?php
include 'db.php';

$user_id = 1; // Example: logged-in user ID
$sql = "SELECT id, title, reminder_date, reminder_time, status FROM reminders WHERE user_id = ? ORDER BY reminder_date, reminder_time";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$reminders = [];
while ($row = $result->fetch_assoc()) {
  $reminders[] = $row;
}

echo json_encode($reminders);
?>