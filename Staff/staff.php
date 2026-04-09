<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../LOGIN1.html"); // adjust path if needed
    exit();
}


// Include backend logic for counts
include 'counts.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Dashboard</title>
  <link rel="stylesheet" href="staff.css?v=18">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">Staff</div>
      <nav>
        <a href="staff.php" class="active">Home</a>
        <a href="my_notices.php">My Notices</a>
        <a href="notifications.php">Notifications</a>
        <a href="profile.php">Profile</a>
        <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>      
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Top bar with welcome + profile -->
      <div class="top-bar">
        <h1>Hi👋,<?php echo $_SESSION['user_id']; ?></h1>
        <div class="profile">
          <span class="profile-name">Staff</span>
          <img src="profile.png" alt="Profile Picture" class="profile-pic">
        </div>
      </div>

      <!-- Stats Section -->
      <div class="stats">
        <div><?php echo $active; ?> <span>Active notices</span></div>
        <div><?php echo $pending; ?> <span>Pending notices</span></div>
        <div><?php echo $expired; ?> <span>Expired notices</span></div>
      </div>

      <!-- See All link -->
      <div class="see-all-container">
        <a href="see_all.php" class="see-all">See all</a>
      </div>

      <!-- Notifications -->
      <div class="notification-box">
        <h2>Notifications from the admin</h2>
     <a href="notifications.php" class="view-btn">
    View messages →
  </a>

      </div>
    </main>
  </div>
</body>
</html>