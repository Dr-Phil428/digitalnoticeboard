<?php
session_start();
// You can include backend logic here if needed, e.g. counts or user info
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Notices</title>
  <link rel="stylesheet" href="staff.css?v=14">
</head>
<body>
  <div class="dashboard">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="logo">Staff</div>
      <nav>
        <a href="staff.php">Home</a>
        <a href="my_notices.php" class="active">My Notices</a>
        <a href="notifications.php">Notifications</a>
        <a href="profile.php">Profile</a>
        <a href="../logout.php" onclick="return confirm('Are you sure you want to logout?');">Logout</a>      </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Top bar -->
      <div class="top-bar">
        <h1>Create a New Notice</h1>
        <div class="profile">
          <span class="profile-name">Staff</span>
          <img src="profile.png" alt="Profile Picture" class="profile-pic">
        </div>
      </div>

      <!-- Create Notice Form -->
      <div class="create-notice">
        <form method="post" action="submitNotice.php" enctype="multipart/form-data" class="notice-form">
          <label for="title">Title</label>
          <input type="text" id="title" name="title" required>

          <label for="content">Content</label>
          <textarea id="content" name="content" required></textarea>

          <label for="category">Category</label>
          <select id="category" name="category">
            <option value="Announcement">Announcement</option>
            <option value="Event">Event</option>
            <option value="Reminder">Reminder</option>
          </select>

          <label for="priority">Priority</label>
          <select id="priority" name="priority">
            <option value="High">High</option>
            <option value="Normal">Normal</option>
            <option value="Low">Low</option>
          </select>

          <label for="posting_date">Posting Date</label>
          <input type="date" id="posting_date" name="posting_date" required>

          <label for="expiry_date">Expiry Date</label>
          <input type="date" id="expiry_date" name="expiry_date" required>

          <label for="attachment">Attachment (Optional)</label>
          <input type="file" id="attachment" name="attachment">

          <button type="submit">Submit Notice</button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>