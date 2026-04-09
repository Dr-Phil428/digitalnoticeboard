<?php
require_once __DIR__ . "/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    // Get the notice title for the notification
    $stmt = $conn->prepare("SELECT title FROM staff_notices WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($title);
    $stmt->fetch();
    $stmt->close();

    if ($action === 'approve') {
        $newStatus = 'active';
        $message = "Your notice '$title' has been approved and is now active.";
    } elseif ($action === 'deny') {
        $newStatus = 'denied';
        $message = "Your notice '$title' has been denied.";
    } else {
        die("Invalid action");
    }

    // Update notice status
    $stmt = $conn->prepare("UPDATE staff_notices SET status=? WHERE id=?");
    $stmt->bind_param("si", $newStatus, $id);
    $stmt->execute();
    $stmt->close();

    // Insert notification into updates table
    $stmt = $conn->prepare("INSERT INTO updates (message, created_at) VALUES (?, NOW())");
    $stmt->bind_param("s", $message);
    $stmt->execute();
    $stmt->close();

    // Redirect back to dashboard
    header("Location: Admin.html");
    exit;
}
?>