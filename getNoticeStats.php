<?php
include 'db.php';

function getCount($status, $conn) {
    $sql = "
    SELECT COUNT(*) AS total 
    FROM (
        SELECT status FROM notices_post WHERE status='$status'
        UNION ALL
        SELECT status FROM notices_save WHERE status='$status'
        UNION ALL
        SELECT status FROM staff_notices WHERE status='$status'
    ) AS combined
";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    return $result->fetch_assoc()['total'];
}

$active  = getCount('active', $conn);
$pending = getCount('pending', $conn);
$expired = getCount('expired', $conn);

echo json_encode([
    "active"  => (int)$active,
    "pending" => (int)$pending,
    "expired" => (int)$expired
]);
?>