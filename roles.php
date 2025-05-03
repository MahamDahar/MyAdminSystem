<?php
include('db.php');
extract($_GET);
if(!isset($_GET['menu_id'])){
    header("Location: logout.php");
}
// Handle DELETE request
if (isset($delete_id)) {
    $sql="DELETE FROM roles WHERE role_id = '".$delete_id."'";
    mysqli_query($conn, $sql);
    $msg['success'] = "Record has been Deleted successfully";
}
// Fetch data for editing
if(isset($edit_id) && $edit_id > 0){
    $sql = "SELECT * FROM `roles` WHERE role_id = '".$edit_id."'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $row1 = mysqli_fetch_assoc($result);
        $role_name = $row1['role_name'];
        
    }
}
extract($_POST);
if(isset($_POST['is_submit'])){
   // Validation
    if(empty($role_name)){
        $error['role_name'] = "Required";
    } 
    if(empty($error)){  
        // Prevent duplicate records
        $duplicateCheckQuery = "SELECT * FROM `roles` 
                                WHERE role_name = '".$role_name."' ";
        if(isset($edit_id) && $edit_id > 0){
            $duplicateCheckQuery .= " AND role_id != '".$edit_id."'";
        }
        $result = mysqli_query($conn, $duplicateCheckQuery);
        if(mysqli_num_rows($result) == 0){
            if(isset($edit_id) && $edit_id > 0){
                // Update record
                $sql = "UPDATE `roles` SET role_name = '".$role_name."'  WHERE role_id = '".$edit_id."'";
                $ok = mysqli_query($conn, $sql);
                if($ok) $msg['success'] = "Record has been updated successfully";
            } else {
                // Insert new record
                $sql = "INSERT INTO `roles` (role_name) VALUES('".$role_name."')";
                $ok = mysqli_query($conn, $sql);
                if($ok) $msg['success'] = "Record has been added successfully";
            }
        } else {
            $error['msg'] = "This record already exists";
        }  
    } 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>roles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <?php include("nav_menu.php"); ?>
    <!-- roles Form -->
    <div class="container mt-5">
        <h2 class="text-center">roles Form</h2>
        <div>
            <?php
             if(isset($error['msg']))  echo "<span style=color:red>".$error['msg']."</span>";
             else if(isset($msg['success']))  echo "<span style=color:green>".$msg['success']."</span>";
            ?>
        </div>
        <form action="" method="POST">
            <input type="hidden" value="Y" name="is_submit" /> 
            <div class="form-group">
                <?php 
                $field_name = "role_name";
                ?>
                 <label for="<?= $field_name?>">Role Name<span style='color: red'> * <?php if(isset($error[$field_name])) echo $error[$field_name]; ?></span></label>
                <input type="text" class="form-control" id="<?= $field_name?>" name="<?= $field_name?>" value="<?php echo isset(${$field_name}) ? ${$field_name} : ''; ?>">
            </div> 
            <button type="submit" class="btn btn-primary btn-block">Submit </button>
        </form>
        
            <?php 
            // Fetch all roles
           $sql= "SELECT a.* FROM roles a ";
            $result = mysqli_query($conn, $sql);
            ?>
         <!-- Roles Table -->
        <h2 class="mt-5">Roles</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Role Name</th> 
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php  
                while ($row = mysqli_fetch_assoc($result)){ ?>
                    <tr>
                        <td><?php echo $row['role_name'];?></td> 
                        <td>
                        <a href="permissions.php?menu_id=<?= $menu_id; ?>&edit_id=<?php echo $row['role_id'];?>" class="btn btn-warning btn-sm">Permissions</a>
                        <a href="roles.php?menu_id=<?= $menu_id; ?>&edit_id=<?php echo $row['role_id'];?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="roles.php?menu_id=<?= $menu_id; ?>&delete_id=<?php echo $row['role_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 Rent System. All rights reserved.</p>
    </footer>
</body>
</html>