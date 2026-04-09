<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "schoolnb");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Count Active notices (from all three tables)
$active_result_post  = $conn->query("SELECT COUNT(*) AS total FROM notices_post WHERE status='active'");
$active_result_save  = $conn->query("SELECT COUNT(*) AS total FROM notices_save WHERE status='active'");
$active_result_staff = $conn->query("SELECT COUNT(*) AS total FROM staff_notices WHERE status='active'");
$active = $active_result_post->fetch_assoc()['total']
        + $active_result_save->fetch_assoc()['total']
        + $active_result_staff->fetch_assoc()['total'];

// Count Pending notices (from all three tables if needed)
$pending_result_post  = $conn->query("SELECT COUNT(*) AS total FROM notices_post WHERE status='pending'");
$pending_result_save  = $conn->query("SELECT COUNT(*) AS total FROM notices_save WHERE status='pending'");
$pending_result_staff = $conn->query("SELECT COUNT(*) AS total FROM staff_notices WHERE status='pending'");
$pending = $pending_result_post->fetch_assoc()['total']
         + $pending_result_save->fetch_assoc()['total']
         + $pending_result_staff->fetch_assoc()['total'];

// Count Expired notices (from all three tables)
$expired_result_post  = $conn->query("SELECT COUNT(*) AS total FROM notices_post WHERE status='expired'");
$expired_result_save  = $conn->query("SELECT COUNT(*) AS total FROM notices_save WHERE status='expired'");
$expired_result_staff = $conn->query("SELECT COUNT(*) AS total FROM staff_notices WHERE status='expired'");
$expired = $expired_result_post->fetch_assoc()['total']
         + $expired_result_save->fetch_assoc()['total']
         + $expired_result_staff->fetch_assoc()['total'];
$conn->close();
?>