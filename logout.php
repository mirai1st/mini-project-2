<?php
// Task 1: destroy session on logout
session_start();
session_destroy();
header("Location: /campus_hub/login.php");
exit();
?>
