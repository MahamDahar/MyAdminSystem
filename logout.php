<?php
session_start();
session_destroy(); // Destroy session
header("Location: login.php?menu_id="); // Redirect to login page
exit();
?>