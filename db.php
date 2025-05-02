<?php
session_start();
$servername = "localhost";  // Server (Use 'localhost' for local XAMPP server)
$username = "root";         // Default XAMPP username
$password = "";             // Default XAMPP password (empty)
$dbname = "schoolsystem";     // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// echo "Connected successfully";

if(isset($_GET['menu_id']) && isset($_SESSION["user_id"])){
   $sql = "SELECT * FROM user_login b 
            INNER JOIN permissions a ON b.role_id = a.role_id
            WHERE a.menu_id = '".$_GET['menu_id']."'
            AND b.`id` = '".$_SESSION['user_id']."'";
    $result2 = mysqli_query($conn, $sql);
    $count2 = mysqli_num_rows($result2);
    if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'SuperAdmin'){
        $count2 = 1;
    } 
    
    if($count2 == 0){  
        header("Location: logout.php");
    } 
} 