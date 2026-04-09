<?php
include 'db.php';
header('Content-Type: application/json');

// 🔹 Query all reminders (active + completed)
$sql = "SELECT id, title, reminder_date, reminder_time, status FROM reminders";
$result = $conn->query($sql);

$events = []; // initialize array

while ($row = $result->fetch_assoc()) {
    $events[] = [
        "id" => $row['id'],
        "title" => $row['title'],
        "start" => $row['reminder_date']."T".$row['reminder_time'],
        // Add a CSS class if completed
        "className" => ($row['status'] === 'completed') ? 'completed-event' : ''
    ];
}

// 🔹 Output JSON for FullCalendar
echo json_encode($events);

$conn->close();
?>