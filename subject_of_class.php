<?php
include('db.php');
extract($_GET);
if(!isset($_GET['menu_id'])){
    header("Location: logout.php");
}
if(isset($delete_id)){
    mysqli_query($conn,"Delete from subject_of_class where subj_of_class_id=$delete_id");
    $msg['success']="This record has been deleted successfully";
}

if(isset($edit_id) AND $edit_id> 0){
$sql="Select * from subject_of_class where subj_of_class_id='".$edit_id."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
    $row1=mysqli_fetch_assoc($result);
    $class_id=$row1['class_id'];
    $subject_id=$row1['subject_id'];
    }
}
extract($_POST);
if(isset($_POST['is_submit'])){
    // if(isset($is_submit))
    if(empty($class_id)) $error['class_id']="Required";
    if(empty($subject_id)) $error['subject_id']="Required";
    if(empty($error)){
        $duplicatecheckquery="  SELECT * from subject_of_class
                                    WHERE class_id='".$class_id."'
                                    AND subject_id='".$subject_id."'";
        if(isset($edit_id)&& $edit_id>0){
                $duplicatecheckquery.= "AND subj_of_class_id != '".$edit_id."'";
        } 
        // echo $duplicatecheckquery."";die;
        $result=mysqli_query($conn,$duplicatecheckquery);
        if(mysqli_num_rows($result)==0){
            if(isset($edit_id) && $edit_id>0){
                $sql="  update subject_of_class SET 
                        class_id='".$class_id."',
                        subject_id='".$subject_id."'
                        where subj_of_class_id='".$edit_id."'";
                $ok=mysqli_query($conn,$sql);
                if($ok){
                    $msg['success']="Record has been updated successfully";
                }
            }
            else{
                $sql="INSERT INTO subject_of_class(class_id,subject_id)
                          VALUES('".$class_id."','".$subject_id."')";
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
$result=mysqli_query($conn,"Select * from subject_of_class");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject of class Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <?php include("nav_menu.php"); ?>
    <!-- Apartment Form -->
    <div class="container mt-5">
        <h2 class="text-center">subject of class form</h2>
        <div>
            <?php
             if(isset($error['msg']))  echo "<span style=color:red>".$error['msg']."</span>";
             else if(isset($msg['success']))  echo "<span style=color:green>".$msg['success']."</span>";
            ?>
        </div>
        <form action="" method="POST">
            <input type="hidden" value="Y" name="is_submit" /> 
            <div class="form-group">
                <label for="class_id">Class Name 
                    <span style='color:red'>*
                        <?php if(isset($error['class_id'])) echo $error['class_id'];?>
                    </span>
                </label><br>
                <select name="class_id" id="class_id" class="form-control">
                    <?php
                    // Fetch all classes from the database
                    $sql = "SELECT * FROM class"; 
                    $result1 = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result1)) {?>
                        <option value='<?php echo $row['class_id'];?>' <?php if(isset($class_id) && $class_id == $row['class_id']) echo "selected"; ?> ><?php echo $row['class_name'];?></option>
                    <?php }?>
                </select>
        </div>

        <div class="form-group">
                <label for="subject_id">
                    Subject Name 
                    <span style='color:red'>*
                        <?php if(isset($error['subject_id'])) echo $error['subject_id'];?>
                    </span>
                </label><br>
                <select name="subject_id" id="subject_id" class="form-control" >
                    <option value="">Select subject</option>
                    <?php
                    // Fetch all subject from the database
                    $sql = "SELECT * FROM subject";
                    $result1 = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result1)) {?>
                        <option value='<?php echo $row['subject_id'];?>' <?php if(isset($subject_id) && $subject_id == $row['subject_id']) echo "selected"; ?> ><?php echo $row['subject_name'];?></option>
                    <?php }?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form> 
    <!-- apartment table -->
    <h2 class="mt-5">subject of class</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>class name</th>
                <th>subject name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($row=mysqli_fetch_assoc($result)){ ?>
        <tr>
            <td><?php echo $row['class_id'];?></td>
            <td><?php echo $row['subject_id'];?></td>
            <td>
            <a href="subject_of_class.php?menu_id=<?= $menu_id;?>&edit_id=<?php echo $row['subj_of_class_id']; ?> " 
            class="btn btn-warning btn-sm">Edit</a>
            <a href="subject_of_class.php?menu_id=<?= $menu_id;?>&delete_id=<?php echo $row['subj_of_class_id']; ?>" 
            class="btn btn-danger btn-sm"
            onclick="return confirm('Are u sure u want to delete this record?');">
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
