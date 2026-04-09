<?php
include __DIR__ . "/db.php";

// Auto-refresh statuses before displaying anything
$conn->query("
  UPDATE notices_save
  SET status = CASE
    WHEN CURDATE() < posting_date THEN 'pending'
    WHEN CURDATE() BETWEEN posting_date AND expiry_date THEN 'active'
    WHEN CURDATE() > expiry_date THEN 'expired'
  END
");

$conn->query("
  UPDATE notices_post
  SET status = CASE
    WHEN CURDATE() BETWEEN `posting date` AND `expiry date` THEN 'active'
    WHEN CURDATE() > `expiry date` THEN 'archived'
    ELSE 'draft'
  END
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Notices Dashboard</title>
  <style>
    .card { border:1px solid #ccc; padding:15px; margin:10px; border-radius:5px; }
    .pending { background:#f9f9d1; }
    .active { background:#d1f9d6; }
    .expired { background:#f9d1d1; }
  </style>
</head>
<body>
  <h1>Notices Dashboard</h1>

  <?php
  // Pending from notices_save
  $pending = $conn->query("SELECT * FROM notices_save WHERE status='pending'");

  echo "<div class='card pending'><h2>Pending Notices</h2>";
  if ($pending && $pending->num_rows > 0) {
      while($row = $pending->fetch_assoc()) {
          echo "<p><strong>{$row['title']}</strong> ({$row['posting_date']} → {$row['expiry_date']})</p>";
      }
  } else {
      echo "<p>No pending notices.</p>";
  }
  echo "</div>";

  // Active from notices_post
  $active = $conn->query("SELECT * FROM notices_post WHERE status='active'");

  echo "<div class='card active'><h2>Active Notices</h2>";
  if ($active && $active->num_rows > 0) {
      while($row = $active->fetch_assoc()) {
          echo "<p><strong>{$row['Title']}</strong> ({$row['posting date']} → {$row['expiry date']})</p>";
      }
  } else {
      echo "<p>No active notices.</p>";
  }
  echo "</div>";

  // Expired (archived) from notices_post
  $expired = $conn->query("SELECT * FROM notices_post WHERE status='archived'");

  echo "<div class='card expired'><h2>Expired Notices</h2>";
  if ($expired && $expired->num_rows > 0) {
      while($row = $expired->fetch_assoc()) {
          echo "<p><strong>{$row['Title']}</strong> ({$row['posting date']} → {$row['expiry date']})</p>";
      }
  } else {
      echo "<p>No expired notices.</p>";
  }
  echo "</div>";
  ?>
</body>
</html>