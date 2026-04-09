<?php
include 'db.php';

$staffId = $_GET['staff_id'];

$sql = "SELECT id, sender, recipient, category, message, reply, created_at 
        FROM messages 
        WHERE recipient = ? 
        ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $staffId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<div class='message-card'>";
    echo "<strong>Staff ID:</strong> " . htmlspecialchars($row['recipient']) . "<br>";
    echo "<strong>Admin Message:</strong> " . htmlspecialchars($row['message']) . "<br>";
    if (!empty($row['reply'])) {
        echo "<strong>Staff Reply:</strong> " . htmlspecialchars($row['reply']) . "<br>";
    } else {
        echo "<em>No reply yet</em><br>";
    }
    echo "<small>Sent on: " . $row['created_at'] . "</small>";
    echo "</div><hr>";
}
?>