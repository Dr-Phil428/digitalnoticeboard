<?php
require_once __DIR__ . "/db.php";

$sql = "SELECT * FROM staff_notices WHERE status='pending'";
$result = $conn->query($sql);

$notices = [];
while ($row = $result->fetch_assoc()) {
    $notices[] = $row;
}

header('Content-Type: application/json');
echo json_encode($notices);
?>