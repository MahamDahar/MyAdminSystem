<?php
include('db.php'); 
// $email      = "maham";
// $password   = "maham123";
if(isset($_SESSION["username"])){
    header("Location: index.php"); // Redirect to index page
    exit();
}
extract($_POST);
if(isset($_POST['is_submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $md5password = md5($password);

    if(empty($email)){
        $error['email']="Required";
    }
    if(empty($password)){
        $error['password']="Required";
    }
    if(empty($error)){

       $sql="SELECT * FROM `user_login` WHERE email = '".$email."' AND `md5_password` = '".$md5password."' ";
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            $row = mysqli_fetch_assoc($result);
            $_SESSION["user_id"]    = $row['id']; // Store email in session
            $_SESSION["user_type"]  = $row['user_type']; // Store email in session
            $_SESSION["email"]      = $email; // Store email in session
            $_SESSION["username"]   = $email; // Store email in session
            $_SESSION["role"]       = $row['role']; // Store role

           // Redirect based on role
           if($row['role'] == 'admin'){
            header("Location: index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }
    else{
        $error['email']="Invalid email or Password";
    }
}
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School system</title>
    <link rel="stylesheet" href="style.css">  <!-- Link to your CSS file -->
</head>
<body>

    <div class="login-container">
        <h2>Login</h2>

        <form action="" method="POST">
            <input type="hidden" value="Y" name="is_submit"/>
            <div class="form-group">
                <label for="email"> email <span style='color:red'>*
                    <?php if(isset($error['email'])) echo $error['email'];?>
                </span></label>
                <input type="text" class="form-control" id="email" name="email"
                 value="<?php if(isset($email)){ echo $email;}?>" placeholder="Enter email">
                </div>
                <div class="form-group">
                <label for="password"> Password <span style='color:red'>*
                    <?php if(isset($error['password'])) echo $error['password'];?>
                </span></label>
                <input type="password" class="form-control" id="password" name="password"
                 value="<?php if(isset($password)){ echo $password;}?>" placeholder="Enter password">
            </div>
            <a href="forgot_password.php">Forgot Password?</a>
            <br></br>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form> 
    </div>

</body>
</html>
