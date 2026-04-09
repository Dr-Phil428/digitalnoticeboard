<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

$conn = new mysqli("localhost", "root", "", "schoolnb");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed']);
    exit;
}

// Step 1: Promote pending notices in both tables
$conn->query("UPDATE notices_post
              SET status='active' 
              WHERE status='pending' AND posting_date <= CURDATE()");
$conn->query("UPDATE notices_save
              SET status='active' 
              WHERE status='pending' AND posting_date <= CURDATE()");

// Step 2: Archive expired notices in both tables
$conn->query("UPDATE notices_post 
              SET status='expired' 
              WHERE expiry_date < CURDATE() 
              AND status='active'");
$conn->query("UPDATE notices_save 
              SET status='expired' 
              WHERE expiry_date < CURDATE() 
              AND status='active'");
$conn->query("UPDATE staff_notices 
              SET status='expired' 
              WHERE expiry_date < CURDATE() AND status='active'");


// Step 3: Fetch active notices from both tables
$sql = "(SELECT id, title AS Title, content, category, priority AS Priority, posting_date, expiry_date, status, attachment, created_at, 'post' AS source
         FROM notices_post
         WHERE status='active' AND expiry_date >= CURDATE())
        UNION ALL
        (SELECT id, title AS Title, content, category, priority AS Priority, posting_date, expiry_date, status, attachment, created_at, 'save' AS source
         FROM notices_save
         WHERE status='active' AND expiry_date >= CURDATE())
        UNION ALL
        (SELECT id, title AS Title, content, category, priority AS Priority, posting_date, expiry_date, status, attachment, created_at, 'staff' AS source
         FROM staff_notices
         WHERE status='active' AND expiry_date >= CURDATE())
        ORDER BY 
            CASE 
                WHEN Priority = 'high' THEN 3
                WHEN Priority = 'medium' THEN 2
                WHEN Priority = 'low' THEN 1
                ELSE 0
            END DESC,
            created_at DESC";



$result = $conn->query($sql);
$notices = [];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $notices[] = [
            'title'       => $row['Title'],
            'content'     => $row['content'],
            'category'    => $row['category'],
            'priority'    => $row['Priority'],
            'postingDate' => $row['posting_date'],
            'expiryDate'  => $row['expiry_date'],
            'status'      => $row['status'],
            'attachment'  => $row['attachment'],
            'source'      => $row['source']
        ];
    }
}

echo json_encode($notices);
$conn->close();
?>