<div class="messages-card">
  <h2>Student Feedback</h2>
  <?php
  $sql = "SELECT * FROM student_feedback ORDER BY created_at DESC";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
      echo "<div class='message-item'>";
      echo "<h3>Student ID: {$row['student_id']} (Grade: {$row['grade']})</h3>";
      echo "<p><strong>Category:</strong> {$row['category']}</p>";
      echo "<p>{$row['message']}</p>";
      echo "<small>Submitted on {$row['created_at']}</small>";
      echo "</div>";
  }
  ?>

  <h2>Parent Feedback</h2>
  <?php
  $sql = "SELECT * FROM parent_feedback ORDER BY created_at DESC";
  $result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
      echo "<div class='message-item'>";
      echo "<h3>Parent (Grade: {$row['grade']})</h3>";
      echo "<p><strong>Category:</strong> {$row['category']}</p>";
      echo "<p>{$row['message']}</p>";
      echo "<small>Email: {$row['email']} | Submitted on {$row['created_at']}</small>";
      echo "</div>";
  }
  ?>
  
</div>