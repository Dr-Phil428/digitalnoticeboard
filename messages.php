<?php
// Fetch data from your endpoint
$messagesJson = file_get_contents("getMessages.php");
$messages = json_decode($messagesJson, true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Messages</title>
  <link rel="stylesheet" href="Admin.css?v=48">
  <style>
    /* Example styling for cards */
    .message-card {
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
      margin-bottom: 15px;
      max-width: 70%;
    }
    .message-green { background-color: #164A41; color: white; }
    .message-mustard { background-color: #333; color: white; }
    .message-details { color: #ffeb3b; font-weight: bold; margin-bottom: 8px; }
    .message-text { color: white; }
  </style>
</head>
<body>
  <h2>Messages</h2>
  <div id="messagesList">
    <?php foreach ($messages as $i => $m): ?>
      <?php
        $cardClass = $i % 2 === 0 ? "message-green" : "message-mustard";
        if ($m['type'] === 'student') {
          $details = "ID: {$m['student_id']} | Grade: {$m['grade']} | Category: {$m['category']}";
        } else {
          $details = "Parent | Grade: {$m['grade']} | Category: {$m['category']} | Email: {$m['email']}";
        }
      ?>
      <div class="message-card <?= $cardClass ?>">
        <div class="message-details"><?= $details ?></div>
        <div class="message-text"><?= htmlspecialchars($m['message']) ?></div>
        <small><?= $m['created_at'] ?></small>
        <?php if ($m['type'] === 'parent'): ?>
          <div class="reply-section">
            <form method="post" action="send_reply.php">
              <input type="hidden" name="email" value="<?= $m['email'] ?>">
              <input type="text" name="reply" placeholder="Write your reply...">
              <button type="submit">Send</button>
            </form>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>