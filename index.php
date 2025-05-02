<?php
include('db.php');
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>school System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- <script src="jquery.js"></script> -->
    <script>
        $(document).ready(function(){
            
        });
    </script>
</head>
<body>
    <!-- Navbar -->
    <?php include("nav_menu.php"); ?>
    <!-- Hero Section -->
    <div class="jumbotron jumbotron-fluid bg-primary text-white text-center">
        <h1 class="display-4">Welcome to School System</h1>
        <p class="lead">"Empowering Young Minds, Shaping Bright Futures!"</p>
    </div>

    <!-- Dashboard Cards -->
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <img src="images/students.jpg" class="card-img-top" alt="student">
                    <div class="card-body">
                        <h5 class="card-title">Students</h5>
                        <p class="card-text">View, add, or update students.</p>
                        <a href="student.php" class="btn btn-primary">Manage students</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/teacher.jpg" class="card-img-top" alt="teacher">
                    <div class="card-body">
                        <h5 class="card-title">Teachers</h5>
                        <p class="card-text">View, add, or update teachers.</p>
                        <a href="teacher.php" class="btn btn-primary">Manage teachers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/class.jpg" class="card-img-top" alt="class">
                    <div class="card-body">
                        <h5 class="card-title">Classes</h5>
                        <p class="card-text">View, add, or update classes.</p>
                        <a href="class.php" class="btn btn-primary">Manage classes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Rent System. All rights reserved.</p>
    </footer>

</body>
</html>
