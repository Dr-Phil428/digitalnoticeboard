<?php
require_once __DIR__ . "/db.php";

$sql = "SELECT message, created_at FROM updates ORDER BY created_at DESC LIMIT 20";
$result = $conn->query($sql);

echo "<h2>🔔 Notifications</h2>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border-bottom:1px solid #ddd; padding:10px; margin-bottom:10px;'>";
        echo "<p>" . htmlspecialchars($row['message']) . "</p>";
        echo "<small style='color:#666;'>Posted on: " . htmlspecialchars($row['created_at']) . "</small>";
        echo "</div>";
    }
} else {
    echo "<p>No notifications yet.</p>";
}
?>