<?php
include('db.php');
extract($_GET); 
if(!isset($_GET['menu_id'])){
    header("Location: logout.php");
}
// Fetch data for editing
if(isset($edit_id) && $edit_id > 0){
    $sql = "SELECT * FROM `roles` WHERE role_id = '".$edit_id."'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        $row1 = mysqli_fetch_assoc($result);
        $role_name = $row1['role_name'];
    }
    $menus = array();
    $sql = "SELECT * FROM `permissions` WHERE role_id = '".$edit_id."'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        
        while( $row = mysqli_fetch_assoc($result)){
            $menus[] = $row['menu_id'];
        } 
    }
} 
extract($_POST);
if(isset($_POST['is_submit'])){
   // Validation
    if(empty($error)){  
        $sql="DELETE FROM permissions WHERE role_id = '".$edit_id."' ";
        mysqli_query($conn, $sql);
        $k = 0;
        foreach($menus as $data){
            $sql = "INSERT INTO `permissions` (role_id, menu_id) VALUES('".$edit_id."', '".$data."')";
            $ok = mysqli_query($conn, $sql);
            if($ok){
                $k++;
            }
        }
        if($k>0){
            $msg['success'] = "Permissions have been set";
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
                <input type="text" class="form-control" id="<?= $field_name?>"  readonly disabled value="<?php echo isset(${$field_name}) ? ${$field_name} : ''; ?>">
            </div> 
            
            <div class="form-group">
                <label for="menus"> Menus <span style=color:red>* <?php if(isset($error['menus'])) echo $error['menus']; ?></span></label><br> 
                <?php
                    $sql="SELECT * from menus ";
                    $result1=mysqli_query($conn,$sql);
                    while($row1=mysqli_fetch_assoc($result1)){ ?>
                         <input type="checkbox" name="menus[]" value="<?php echo $row1['menu_id'];?>" <?php if(isset($menus) && in_array($row1['menu_id'], $menus)){ echo " checked ";}?> > <?php echo $row1['menu_name'];?> <br>
                <?php }?>
            </div> 
            <button type="submit" class="btn btn-primary btn-block">Submit </button>
        </form>
            <?php 
            // Fetch all roles
           $sql= "SELECT a.* FROM roles a ";
            $result = mysqli_query($conn, $sql);
            ?>
         <!-- Rent Payments Table -->
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
                            <a href="roles.php?edit_id=<?php echo $row['role_id'];?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="roles.php?delete_id=<?php echo $row['role_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
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