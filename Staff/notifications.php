<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../LOGIN1.html");
    exit();
}

include '../db.php'; // adjust path depending on where db.php is
$staff_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications</title>
  <link rel="stylesheet" href="staff.css?v=18">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">Staff</div>
      <nav>
        <a href="staff.php">Home</a>
        <a href="my_notices.php">My Notices</a>
        <a href="notifications.php" class="active">Notifications</a>
        <a href="profile.php">Profile</a>
        <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <div class="top-bar">
        <h1>Hi👋, <?php echo $_SESSION['user_id']; ?></h1>
        <div class="profile">
          <span class="profile-name">Staff</span>
          <img src="profile.png" alt="Profile Picture" class="profile-pic">
        </div>
      </div>

      <!-- Notifications Section -->
      <div class="notifications-container">
        <h2>Messages from Admin</h2>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM messages WHERE recipient='$staff_id' ORDER BY created_at DESC");

        while($row = mysqli_fetch_assoc($result)) {
          echo "<div class='notification-card'>";
          echo "<p><strong>Message:</strong> {$row['message']}</p>";
          echo "<p><strong>Category:</strong> {$row['category']}</p>";
          echo "<small>{$row['created_at']}</small>";

          if (!empty($row['reply'])) {
            echo "<p><strong>Your Reply:</strong> {$row['reply']}</p>";
          } else {
            echo "<form class='reply-form' action='replyMessage.php' method='POST'>";
            echo "<input type='hidden' name='id' value='{$row['id']}'>";
            echo "<textarea name='reply' placeholder='Write your reply...'></textarea>";
            echo "<button type='submit'>Send Reply</button>";
            echo "</form>";
          }

          echo "</div>";
        }
        ?>
      </div>
    </main>
  </div>
</body>
</html>