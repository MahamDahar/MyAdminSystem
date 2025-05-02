<?php
include('db.php');

$email = "";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Backend validation: Check if email is empty
    if (empty($email)) {
        $error = "email is required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if email exists in the database
        $query = "SELECT * FROM user_login WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Generate a reset token
            $token = bin2hex(random_bytes(50)); // Create a random token
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); // Token expires in 1 hour

            // Save token in the database
            mysqli_query($conn, "UPDATE user_login SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'");

            // Send email with the reset link
            $reset_link = "http://yourwebsite.com/reset_password.php?token=$token";
            mail($email, "Password Reset", "Click here to reset your password: $reset_link");

            $success = "A password reset link has been sent to your email!";
        } else {
            $error = "email not found!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget</title>
    <link rel="stylesheet" href="style.css">  <!-- Link to your CSS file -->
</head>
<body>
<div class="login-container">
        <h2>Forget Password</h2>

    <form action="" method="POST">
            <input type="hidden" value="Y" name="is_submit" />
            <div class="form-group">
                <label for="email"> email <span style='color:red'>*
                    <?php if(isset($error['email'])) echo $error['email'];?>
                </span></label>
            <input type="text" class="form-control" id="email" name="email"
                 value="<?php if(isset($email)){ echo $email;}?>" placeholder="Enter email" >
            </div>
        <button type="submit" class="btn btn-primary btn-block"> Reset PASSWORD </button>
    </form> 
</div>
</body>
</html>