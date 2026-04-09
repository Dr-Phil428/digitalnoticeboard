<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "schoolnb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Promote pending → active
$conn->query("UPDATE notices_post
              SET status='active'
              WHERE status='pending' AND posting_date <= CURDATE()");
$conn->query("UPDATE notices_save
              SET status='active'
              WHERE status='pending' AND posting_date <= CURDATE()");

// Step 2: Archive expired
$conn->query("UPDATE notices_post
              SET status='expired'
              WHERE expiry_date < CURDATE() AND status='active'");
$conn->query("UPDATE notices_save
              SET status='expired'
              WHERE expiry_date < CURDATE() AND status='active'");

// Step 3: Fetch active notices from both tables
$sql = "(SELECT id, Title, content, category, posting_date, expiry_date, status, created_at, 'post' AS source
         FROM notices_post
         WHERE status = 'active' AND expiry_date >= CURDATE())
        UNION ALL
        (SELECT id, Title, content, category, posting_date, expiry_date, status, created_at, 'save' AS source
         FROM notices_save
         WHERE status = 'active' AND expiry_date >= CURDATE())
        UNION ALL
        (SELECT id, Title, content, category, posting_date, expiry_date, status, created_at, 'staff' AS source
         FROM staff_notices
         WHERE status = 'active' AND expiry_date >= CURDATE())
        ORDER BY created_at DESC";
$result = $conn->query($sql);

$html = '';

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $html .= '<div style="border-bottom: 1px solid #ddd; padding: 10px 0; margin-bottom: 10px;">';
        $html .= '<h4>' . htmlspecialchars($row['Title']) . '</h4>';
        $html .= '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
        $html .= '<small style="color: #666;">Category: ' . htmlspecialchars($row['category']) .
                 ' | Posted: ' . htmlspecialchars($row['posting_date']) .
                 ' | Expiry: ' . htmlspecialchars($row['expiry_date']) .
                 ' | Source: ' . htmlspecialchars($row['source']) . '</small>';
        $html .= '</div>';
    }
} else {
    $html = '<p>No active posts found.</p>';
}

echo $html;

$conn->close();
?>