<?php
session_start();
session_unset(); // remove all session variables
session_destroy(); // destroy the session

header("Location: LOGIN1.html"); // back to login page
exit();
?>