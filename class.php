<?php
include('db.php');
extract($_GET);
if(isset($delete_id)){
    mysqli_query($conn,"Delete from class where class_id=$delete_id");
    $msg['success']="This record has been deleted successfully";
}

if(isset($edit_id) AND $edit_id> 0){
$sql="Select * from class where class_id='".$edit_id."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
    $row1=mysqli_fetch_assoc($result);
    $class_name=$row1['class_name'];
    }
}
extract($_POST);
if(isset($_POST['is_submit'])){
    // if(isset($is_submit))
    if(empty($class_name)) $error['class_name']="Required";
    if(empty($error)){
$duplicatecheckquery="  SELECT * from class 
                                WHERE class_name='".$class_name."'";
    if(isset($edit_id)&& $edit_id>0){
            $duplicatecheckquery.= "AND class_id != '".$edit_id."'";
    } 
        // echo $duplicatecheckquery."";die;
        $result=mysqli_query($conn,$duplicatecheckquery);
        if(mysqli_num_rows($result)==0){
            if(isset($edit_id) && $edit_id>0){
                $sql="  update class SET 
                        class_name='".$class_name."'
                        where class_id='".$edit_id."'";
                $ok=mysqli_query($conn,$sql);
                if($ok){
                    $msg['success']="Record has been updated successfully";
                }
            }
            else{
                $sql="INSERT INTO class(class_name)
                          VALUES('".$class_name."')";
                $ok= mysqli_query($conn,$sql);
                if($ok){
                    $msg['success']="Record has been added successfully";
                }
            }
        }
        else{
            $error['msg']="This record has already exist";
        }
    }
}
$result=mysqli_query($conn,"Select * from class");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School system</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <?php include("nav_menu.php"); ?>
    <!-- Class Form -->
    <div class="container mt-5">
        <h2 class="text-center">CLASS FORM</h2>
        <div>
            <?php
             if(isset($error['msg']))  echo "<span style=color:red>".$error['msg']."</span>";
             else if(isset($msg['success']))  echo "<span style=color:green>".$msg['success']."</span>";
            ?>
        </div>
        <form action="" method="POST">
            <input type="hidden" value="Y" name="is_submit" /> 
            <div class="form-group">
                <label for="class_name">Class Name <span style='color:red'>*
                    <?php if(isset($error['class_name'])) echo $error['class_name'];
                ?>
                </span></label>
                <input type="text" class="form-control" id="class_name" name="class_name" value="<?php if(isset($class_name)){ echo $class_name;}?>" placeholder="Enter class name" >
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form> 
    <!-- Class table -->
    <h2 class="mt-5">CLASS</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Class Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($row=mysqli_fetch_assoc($result)){ ?>
        <tr>
            <td><?php echo $row['class_name'];?></td>
            <td>
            <a href="subject_of_class.php?menu_id=<?php echo $menu_id; ?>&class_id=<?php echo $row['class_id']; ?>" class="btn btn-primary btn-sm">Subjects</a>
            <a href="class.php?edit_id=<?php echo $row['class_id']; ?> " class="btn btn-warning btn-sm">Edit</a> 
            <a href="class.php?delete_id=<?php echo $row['class_id']; ?>"  class="btn btn-danger btn-sm" onclick="return confirm('Are u sure u want to delete this record?');">
            Delete</a>  
        </td>
        </tr>
    <?php } ?>
    </tbody>
    </table>
    </div>
    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 School System. All rights reserved.</p>
    </footer>
</body>
</html>
