<?php
include('db.php');
extract($_GET);

if(!isset($_GET['menu_id'])){
    header("Location: logout.php");
}
if(isset($delete_id)){
    mysqli_query($conn,"DELETE FROM user_login WHERE id=$delete_id"); 
    $msg['success'] = "This record has been deleted successfully";
}

if(isset($edit_id) && $edit_id > 0){
    $sql = "SELECT * FROM user_login WHERE id = '".$edit_id."'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $row1           = mysqli_fetch_assoc($result);
        $email          = $row1['email'];
        $password       = $row1['password'];
        $image          = $row1['image'];
        $document_name  = $row1['document_name'];
        $role_id        = $row1['role_id'];
    }
}

extract($_POST);
if(isset($_POST['is_submit'])){ 

    if(empty($email)) $error['email'] = "Required";
    if(empty($password)) $error['password'] = "Required";

    if(isset($_FILES['image']) && $_FILES["image"]["name"] != '') {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $image_type = $_FILES['image']['type'];
        $image_size = $_FILES['image']['size'];
        if (!in_array($image_type, $allowed_types)) {
            $error['image'] = "Only JPG, PNG, and GIF files are allowed.";
        } elseif ($image_size > 2 * 1024 * 1024) { // 2MB limit
            $error['image'] = "Image size must be less than 2MB.";
        } 
    } 

    if(empty($error)){
        $md5_password = md5($password);
        $upload_dir = "uploads/";

        $image = "";
        if(isset($_FILES['image']) && $_FILES["image"]["name"] != '') {
            $image_name = time() . "_" . basename($_FILES["image"]["name"]);
            $image_path = $upload_dir . $image_name;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                $image = $image_name;
            }
        }

        $document_name = ""; 
        if(isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
            $document_name = time() . "_" . basename($_FILES["document"]["name"]);
            $document_path = $upload_dir . $document_name;
            $allowed_types = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png'];
            $document_type = $_FILES['document']['type'];
            $document_size = $_FILES['document']['size'];
            if (in_array($document_type, $allowed_types) && $document_size <= 30 * 1024 * 1024) {
                move_uploaded_file($_FILES["document"]["tmp_name"], $document_path);
            } 
        }

        $duplicatecheckquery = "SELECT * FROM user_login WHERE email = '".$email."' ";
        if(isset($edit_id) && $edit_id > 0){
            $duplicatecheckquery .= " AND id != '".$edit_id."'";
        } 
        $result = mysqli_query($conn, $duplicatecheckquery);
        if(mysqli_num_rows($result) == 0){
            if(isset($edit_id) && $edit_id > 0){
                $sql = "UPDATE user_login SET 
                                                email           = '".$email."', 
                                                password        = '".$password."', 
                                                md5_password    = '".$md5_password."', 
                                                image           = '".$image."', 
                                                role_id         = '".$role_id."', 
                                                document_name   = '".$document_name."' 
                        WHERE id = '".$edit_id."'";
                $ok = mysqli_query($conn, $sql);
                if($ok) $msg['success'] = "Record has been updated successfully";
            } else {
               $sql = "INSERT INTO user_login(email, password, md5_password, image, document_name, role_id) 
                        VALUES('".$email."', '".$password."', '".$md5_password."', '".$image."', '".$document_name."', '".$role_id."')";
                $ok = mysqli_query($conn, $sql);
                if($ok) $msg['success'] = "Record has been added successfully";
            }
        } else {
            $error['msg'] = "This record already exists";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM user_login");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Module</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include("nav_menu.php"); ?>

    <div class="container mt-5">
        <h2 class="text-center">User Details</h2>
        <div>
            <?php
            if(isset($error['msg'])) echo "<span style='color:red'>".$error['msg']."</span>";
            else if(isset($msg['success'])) echo "<span style='color:green'>".$msg['success']."</span>";
            ?>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" value="Y" name="is_submit" /> 
            <div class="form-group">
                <label for="email">User Name <span style='color:red'>*</span></label>
                <input type="text" class="form-control" id="email" name="email" value="<?php if(isset($email)) echo $email; ?>" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="password">Password <span style="color:red">*</span></label>
                <input type="password" class="form-control" id="password" name="password" value="<?php if(isset($password)) echo $password; ?>" placeholder="Enter Password">
            </div>
            <div class="form-group">
                <label for="role_id">ROLES <span style=color:red>* <?php if(isset($error['roles_id'])) echo $error['role_id']; ?></span></label>
                <select name="role_id" class="form-control">
                    <option value="">select roles</option>
                    <?php
                    $sql="SELECT role_id,role_name from roles";
                    $result1=mysqli_query($conn,$sql);
                    while($row1=mysqli_fetch_assoc($result1)){ ?>
                        <option value="<?php echo $row1['role_id'];?>" <?php if (isset($role_id) && $role_id == $row1['role_id']){ echo'selected';}?>><?php echo $row1['role_name'];?></option>
                    <?php }?>
               </select>
            </div>
            <div class="form-group">
                <label for="image">Select an image: <span style="color:red">*</span></label><br>
                <input type="file" name="image" id="image">
                <?php if (!empty($image)) { ?>
                    <br>
                    <img src="uploads/<?php echo $image; ?>" width="100" height="100" style="margin-top:10px;">
                <?php } ?>
            </div>
            <div class="form-group">
                <label for="document">Select a document: <span style="color:red">*</span></label><br>
                <input type="file" name="document" id="document">
            </div> 
            <button type="submit" class="btn btn-primary btn-block">Done</button>
        </form> 

        <h2 class="mt-5">Users List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Password</th>
                    <th>role</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //fetch all record
                $sql="  SELECT a.*, b.`role_name` 
                        FROM user_login a
                        LEFT JOIN `roles` b ON b.`role_id` = a.`role_id`";
                $result=mysqli_query($conn,$sql);
                while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['email']; ?></td>
                    <td>*****</td>
                    <td><?php echo $row['role_name']; ?></td>
                    <td>
                        <?php if (!empty($row['image'])) { ?>
                            <img src="uploads/<?php echo $row['image']; ?>" width="50">
                        <?php } else { echo "No Image"; } ?>
                    </td>
                    <td>
                        <a href="users.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="users.php?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user account?');">Delete</a>  
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
