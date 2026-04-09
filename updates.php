<?php
// Make sure db.php is included before this file
function addUpdate($staffId, $message) {
    global $conn; // uses the connection from db.php
    $sql = "INSERT INTO updates (staff_id, message, created_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $staffId, $message);
    $stmt->execute();
}
?>