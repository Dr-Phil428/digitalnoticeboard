<?php
$conn = new mysqli('localhost', 'root', '', 'schoolnb');
$sql = "DESCRIBE notices_post";
$res = $conn->query($sql);
while($row = $res->fetch_assoc()) echo $row['Field'] . " - " . $row['Type'] . "\n";
?>
