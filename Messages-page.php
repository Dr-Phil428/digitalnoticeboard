<?php
include 'db.php';
?>

<style>
.messages-card {
  height: 400px;
  overflow-y: auto;
  background: #164A41;
  color: #FFFFFF;
  border-radius: 15px;
  padding: 20px;
}
.message-item {
  background: rgba(255,255,255,0.1);
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 8px;
}
.message-item h3 {
  margin: 0 0 5px;
  font-size: 1.1em;
  color: #FFD700;
}
.message-item small {
  color: #ccc;
}
</style>

<div class="messages-card">

<?php
$sql = "SELECT * FROM student_feedback ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='message-item'>";
        echo "<h3>Student ID: {$row['student_id']} (Grade: {$row['grade']})</h3>";
        echo "<p><strong>Category:</strong> {$row['category']}</p>";
        echo "<p>{$row['message']}</p>";
        echo "<small>Submitted on {$row['created_at']}</small>";
        echo "</div>";
    }
} else {
    echo "<p>No student feedback yet.</p>";
}

$sql = "SELECT * FROM parent_feedback ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='message-item'>";
        echo "<h3>Parent (Grade: {$row['grade']})</h3>";
        echo "<p><strong>Category:</strong> {$row['category']}</p>";
        echo "<p>{$row['message']}</p>";
        echo "<small>Email: {$row['email']} | Submitted on {$row['created_at']}</small>";
        echo "</div>";
    }
} else {
    echo "<p>No parent feedback yet.</p>";
}

$conn->close();
?>

</div>