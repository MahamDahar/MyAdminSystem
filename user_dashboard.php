<?php
include('db.php'); 

// Check if the user is logged in and has the role of user
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php"); // Redirect unauthorized users to login
    exit();
}

echo "<h1>Welcome, User!</h1>";
echo "<a href='logout.php'>Logout</a>";
?>
