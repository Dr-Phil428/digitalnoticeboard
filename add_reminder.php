<?php
include 'db1.php';

$title = $_POST['title'];
$description = $_POST['description'];
$date = $_POST['date'];
$time = $_POST['time'];

$sql = "INSERT INTO reminders (title, description, reminder_date, reminder_time) 
        VALUES ('$title', '$description', '$date', '$time')";

if ($conn->query($sql) === TRUE) {
    echo "Reminder set successfully";
} else {
    echo "Error: " . $conn->error;
}
?>
