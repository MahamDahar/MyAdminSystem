<?php
include('db.php'); 

// Check if the user is logged in and has the role of admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redirect unauthorized users to login
    exit();
}

echo "<h1>Welcome, Admin!</h1>";
echo "<a href='logout.php'>Logout</a>";
?>