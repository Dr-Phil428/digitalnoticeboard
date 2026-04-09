<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Notifications</title>
  <link rel="stylesheet" href="notifications.css">
</head>
<body>
    <div class="topbar">
    <div class="logo">Admin</div>
    <nav class="nav-links">
      <a href="#" onclick="showSection('dashboard')">Dashboard</a>
      <a href="#" onclick="showSection('Notices')">Notices</a>
      <a href="#" onclick="showSection('Reports')">Reports</a>
      <a href="admin_notifications.php" onclick="showSection('Notifications')">Notifications</a>
      <a href="#" onclick="showSection('Reminders')">Reminders</a>
      <a href="#" onclick="showSection('profile-page')">Profile</a>
      <a href="#" id="logoutLink">Logout</a>
    </nav>
  </div>
  <div class="container">
    <h1>Notifications Page</h1>
    <div class="grid">
      
      <!-- Left column: Send notification -->
      <section class="send-section">
        <h2>Send Notification</h2>
        <form method="post" action="send_notification.php" class="send-form">
          <label for="staffId">Staff's ID</label>
          <input type="text" id="staffId" name="staffId" required>

          <label for="message">Message</label>
          <textarea id="message" name="message" required></textarea>

          <button type="submit" class="btn">Send</button>
        </form>
      </section>

      <!-- Right column: Messages from staff -->
      <section class="messages-section">
        <h2>Messages from Staff</h2>

        <!-- Example message card -->
        <div class="message-card">
          <p><strong>Staff SN100:</strong> Thank you for the update, admin.</p>
          <form method="post" action="admin_reply.php" class="reply-form">
            <input type="hidden" name="reply_to" value="1">
            <input type="hidden" name="recipient_id" value="100">
            <textarea name="reply_message" placeholder="Write your reply..."></textarea>
            <button type="submit" class="btn btn-reply">Reply</button>
          </form>
        </div>

        <!-- More message cards will be generated dynamically with PHP -->
      </section>
    </div>
  </div>
</body>
</html>