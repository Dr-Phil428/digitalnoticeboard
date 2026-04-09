<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../LOGIN1.html");
    exit();
}

include '../db.php'; // db.php defines $conn

$userId = $_SESSION['user_id'];

// Fetch user details from DB
$result = $conn->query("SELECT first_name, last_name, email,  role FROM users WHERE user_id='$userId'");
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Profile</title>
  <link rel="stylesheet" href="staff.css?v=14">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">Staff</div>
      <nav>
        <a href="staff.php">Home</a>
        <a href="my_notices.php">My Notices</a>
        <a href="notifications.php">Notifications</a>
        <a href="profile.php" class="active">Profile</a>
        <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      
      <!-- Success message -->
<!-- Success message -->
<?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
  <div id="success-message" style="color:white; font-weight:bold; background:green; padding:8px; border-radius:4px;">
    Profile updated successfully!
  </div>
  <script>
    // Hide the message after 5 seconds
    setTimeout(function() {
      var msg = document.getElementById('success-message');
      if (msg) {
        msg.style.transition = "opacity 1s"; // fade effect
        msg.style.opacity = "0";
        setTimeout(function() { msg.style.display = "none"; }, 1000);
      }
    }, 5000);
  </script>
<?php endif; ?>
      <!-- Profile Card -->
      <div class="profile-card">
        <div class="profile-header">
          <div class="avatar">👤</div>
          <h2><?php echo htmlspecialchars($user['first_name'] . " " . $user['last_name']); ?></h2>
          <p><?php echo htmlspecialchars($user['email']); ?></p>
          <span class="role"><?php echo ucfirst(htmlspecialchars($user['role'])); ?></span>
        </div>

        <h3>Personal Information</h3>
        <form action="update_profile.php" method="POST">
          <label>First Name:</label>
          <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>

          <label>Last Name:</label>
          <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>

          <label>Email Address:</label>
          <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

          
          <button type="submit" class="save-btn">Save Changes</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>