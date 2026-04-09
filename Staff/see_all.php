<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "schoolnb");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$post_result = $conn->query("SELECT title, content, posting_date, expiry_date, status 
                             FROM notices_post 
                             ORDER BY posting_date DESC");

// Fetch from notices_save (active + expired)
$save_result = $conn->query("SELECT title, content, posting_date, expiry_date, status 
                             FROM notices_save 
                             ORDER BY posting_date DESC");

// Fetch from staff_notices (active + expired)
$staff_result = $conn->query("SELECT title, content, posting_date, expiry_date, status 
                              FROM staff_notices 
                              ORDER BY posting_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Notices</title>
  <link rel="stylesheet" href="staff.css?v=9">
</head>
<body>
  <div class="dashboard">
    <aside class="sidebar">
      <div class="logo">Staff</div>
      <nav>
        <a href="staff.php">Home</a>
        <a href="my_notices.php">My Notices</a>
        <a href="see_all.php" class="active">See all</a>
        <a href="notifications.php">Notifications</a>
        <a href="profile.php">Profile</a>
        <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>      </nav>
    </aside>

    <main class="main-content">
      <h1>All Notices</h1>

      <!-- Active Notices -->
      <h2>Active Notices</h2>
      <div class="notice-grid">
        <?php
        // Active from notices_post
        if ($post_result->num_rows > 0) {
          $post_result->data_seek(0);
          while($row = $post_result->fetch_assoc()) {
            if ($row['status'] === 'active') {
              echo "<div class='notice-card active'>";
              echo "<h3>".htmlspecialchars($row['title'])."</h3>";
              echo "<p>".htmlspecialchars($row['content'])."</p>";
              echo "<small>Posted: ".htmlspecialchars($row['posting_date'])." | Expires: ".htmlspecialchars($row['expiry_date'])."</small>";
              echo "</div>";
            }
          }
        }
        // Active from notices_save
        if ($save_result->num_rows > 0) {
          $save_result->data_seek(0);
          while($row = $save_result->fetch_assoc()) {
            if ($row['status'] === 'active') {
              echo "<div class='notice-card active'>";
              echo "<h3>".htmlspecialchars($row['title'])."</h3>";
              echo "<p>".htmlspecialchars($row['content'])."</p>";
              echo "<small>Posted: ".htmlspecialchars($row['posting_date'])." | Expires: ".htmlspecialchars($row['expiry_date'])."</small>";
              echo "</div>";
            }
          }
        }
        if ($staff_result->num_rows > 0) {
        $staff_result->data_seek(0);
        while($row = $staff_result->fetch_assoc()) {
         if ($row['status'] === 'active') {
            echo "<div class='notice-card active'>";
            echo "<h3>".htmlspecialchars($row['title'])."</h3>";
            echo "<p>".htmlspecialchars($row['content'])."</p>";
            echo "<small>Posted: ".htmlspecialchars($row['posting_date'])." | Expires: ".htmlspecialchars($row['expiry_date'])."</small>";
            echo "</div>";
      }
    }
  }
  

        ?>
      </div>

      <!-- Expired Notices -->
      <h2>Expired Notices</h2>
      <div class="notice-grid">
        <?php
        // Expired from notices_post
        if ($post_result->num_rows > 0) {
          $post_result->data_seek(0);
          while($row = $post_result->fetch_assoc()) {
            if ($row['status'] === 'expired') {
              echo "<div class='notice-card expired'>";
              echo "<h3>".htmlspecialchars($row['title'])."</h3>";
              echo "<p>".htmlspecialchars($row['content'])."</p>";
              echo "<small>Posted: ".htmlspecialchars($row['posting_date'])." | Expired: ".htmlspecialchars($row['expiry_date'])."</small>";
              echo "</div>";
            }
          }
        }
        // Expired from notices_save
        if ($save_result->num_rows > 0) {
          $save_result->data_seek(0);
          while($row = $save_result->fetch_assoc()) {
            if ($row['status'] === 'expired') {
              echo "<div class='notice-card expired'>";
              echo "<h3>".htmlspecialchars($row['title'])."</h3>";
              echo "<p>".htmlspecialchars($row['content'])."</p>";
              echo "<small>Posted: ".htmlspecialchars($row['posting_date'])." | Expired: ".htmlspecialchars($row['expiry_date'])."</small>";
              echo "</div>";
            }
          }
        }
        if ($staff_result->num_rows > 0) {
         $staff_result->data_seek(0);
        while($row = $staff_result->fetch_assoc()) {
        if ($row['status'] === 'expired') {
        echo "<div class='notice-card expired'>";
        echo "<h3>".htmlspecialchars($row['title'])."</h3>";
        echo "<p>".htmlspecialchars($row['content'])."</p>";
        echo "<small>Posted: ".htmlspecialchars($row['posting_date'])." | Expired: ".htmlspecialchars($row['expiry_date'])."</small>";
        echo "</div>";
      }
    }
  }

        ?>
      </div>
    </main>
  </div>
</body>
</html>
<?php $conn->close(); ?>