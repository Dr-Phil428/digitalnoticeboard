<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "schoolnb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch expired notices from both tables
$sql = "(SELECT id, Title, content, category, posting_date, expiry_date, status, created_at, 'post' AS source
         FROM notices_post
         WHERE status = 'expired' AND expiry_date <= CURDATE())
        UNION ALL
        (SELECT id, Title, content, category, posting_date, expiry_date, status, created_at, 'save' AS source
         FROM notices_save
         WHERE status = 'expired' AND expiry_date <= CURDATE())
        UNION ALL
        (SELECT id, Title, content, category, posting_date, expiry_date, status, created_at, 'staff' AS source
         FROM staff_notices
         WHERE status = 'expired' AND expiry_date <= CURDATE())
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
                 ' | Expired: ' . htmlspecialchars($row['expiry_date']) .
                 ' | Source: ' . htmlspecialchars($row['source']) . '</small>';
        $html .= '</div>';
    }
} else {
    $html = '<p>No expired posts found.</p>';
}

echo $html;

$conn->close();
?>