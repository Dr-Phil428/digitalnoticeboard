<?php
include __DIR__ . "/db.php";

// Query pending notices from notices_save
$result = $conn->query("SELECT * FROM notices_save WHERE status='pending'");

if (!$result) {
    echo "<p style='color:red;'>Failed to fetch pending notices.</p>";
    exit;
}

if ($result->num_rows > 0) {
    $html = "";
    while ($row = $result->fetch_assoc()) {
      $html .= '<div style="border-bottom: 1px solid #ddd; padding: 10px 0; margin-bottom: 10px;">';
        $html .= '<h4>' . htmlspecialchars($row['title']) . '</h4>';
        $html .= '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
        $html .= '<small style="color: #666;">category: ' . htmlspecialchars($row['category']) . ' | Posted: ' . htmlspecialchars($row['posting_date']) . ' | Expired: ' . htmlspecialchars($row['expiry_date']) . '</small>';
        $html .= '</div>';  
    }
    echo $html;
} else {
    echo "<p>No pending notices.</p>";
}
?>