<?php
include '../db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id    = $_POST['id'];
    $reply = $_POST['reply'];

    $sql = "UPDATE messages SET reply='$reply' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: notifications.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>