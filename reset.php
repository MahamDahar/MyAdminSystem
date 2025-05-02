<?php
include('db.php');

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Backend validation
    if (empty($new_password) || empty($confirm_password)) {
        $error = "Both password fields are required!";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Verify if the token is valid and not expired
        $query = "SELECT * FROM users WHERE reset_token = '$token' AND token_expiry > NOW()";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // Hash the password before saving
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the new password
            mysqli_query($conn, "UPDATE users SET password='$hashed_password', reset_token=NULL, token_expiry=NULL WHERE reset_token='$token'");
            $success = "Password reset successfully!";
        } else {
            $error = "Invalid or expired token!";
        }
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
        <h2>Forget Password</h2>

        <form action="" method="POST">
            <input type="hidden" value="Y" onsubmit="return validateForm()">
            <div class="form-group">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
                <label for="new password">new password<span style='color:red'>*
                    <?php if(isset($error['new_password'])) echo $error['new_password'];?>
                </span></label>
                <input type="password" class="form-control" id="new_password" name="new_password"
                 value="<?php if(isset($new_password)){ echo $new_password;}?>" placeholder="new password" >
                 <label for="confirm password">confirm password<span style='color:red'>*
                    <?php if(isset($error['confirm_password'])) echo $error['confirm_password'];?>
                </span></label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                 value="<?php if(isset($confirm_password)){ echo $confirm_password;}?>" placeholder=" confirm password" >
                </div>
        <button type="submit" class="btn btn-primary btn-block">Reset PASSWORD</button>
        </form> 
    </div>
    <script>
    function validateForm() {
        let password = document.getElementById("password").value;
        let confirm_password = document.getElementById("confirm_password").value;

        if (password.trim() === "" || confirm_password.trim() === "") {
            alert("Both password fields are required!");
            return false;
        }
        if (password !== confirm_password) {
            alert("Passwords do not match!");
            return false;
        }
        if (password.length < 6) {
            alert("Password must be at least 6 characters!");
            return false;
        }
        return true;
    }
</script> 
</body>
</html>