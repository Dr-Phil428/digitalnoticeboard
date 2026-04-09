<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// include the db connection
include 'db.php';

$sql = "(SELECT title, content, posting_date, expiry_date, status, 'post' AS source
         FROM notices_post WHERE status='active' AND expiry_date >= CURDATE())
        UNION ALL
        (SELECT title, content, posting_date, expiry_date, status, 'save' AS source
         FROM notices_save WHERE status='active' AND expiry_date >= CURDATE())
        UNION ALL
        (SELECT title, content, posting_date, expiry_date, status, 'staff' AS source
         FROM staff_notices WHERE status='active' AND expiry_date >= CURDATE())
        ORDER BY posting_date DESC";

$result = $conn->query($sql);
$notices = [];

if (!$result) {
    echo json_encode(['error' => $conn->error]);
    exit;
}

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $notices[] = [
            'title'        => $row['title'],
            'content'      => $row['content'],
            'source'       => $row['source'],
        ];
    }
}

echo json_encode($notices);
$conn->close();
?>
