<?php
include('db.php');
extract($_GET);
if(!isset($_GET['menu_id'])){
    header("Location: logout.php");
}
if(isset($delete_id)){
    mysqli_query($conn,"Delete from subject where subject_id=$delete_id");
    $msg['success']="This record has been deleted successfully";
}

if(isset($edit_id) AND $edit_id> 0){
$sql="Select * from subject where subject_id='".$edit_id."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
    $row1=mysqli_fetch_assoc($result);
    $subject_name=$row1['subject_name'];
    }
}
extract($_POST);
if(isset($_POST['is_submit'])){
    // if(isset($is_submit))
    if(empty($subject_name)) $error['subject_name']="Required";
    if(empty($error)){
$duplicatecheckquery="  SELECT * from subject 
                                WHERE subject_name='".$subject_name."'";
    if(isset($edit_id)&& $edit_id>0){
            $duplicatecheckquery.= "AND subject_id != '".$edit_id."'";
    } 
        // echo $duplicatecheckquery."";die;
        $result=mysqli_query($conn,$duplicatecheckquery);
        if(mysqli_num_rows($result)==0){
            if(isset($edit_id) && $edit_id>0){
                $sql="  update subject SET 
                        subject_name='".$subject_name."'
                        where subject_id='".$edit_id."'";
                $ok=mysqli_query($conn,$sql);
                if($ok){
                    $msg['success']="Record has been updated successfully";
                }
            }
            else{
                $sql="INSERT INTO subject(subject_name)
                          VALUES('".$subject_name."')";
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
$result=mysqli_query($conn,"Select * from subject");
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
    <!-- Subject Form -->
    <div class="container mt-5">
        <h2 class="text-center">SUBJECT FORM</h2>
        <div>
            <?php
             if(isset($error['msg']))  echo "<span style=color:red>".$error['msg']."</span>";
             else if(isset($msg['success']))  echo "<span style=color:green>".$msg['success']."</span>";
            ?>
        </div>
        <form action="" method="POST">
            <input type="hidden" value="Y" name="is_submit" /> 
            <div class="form-group">
                <label for="subject_name">Subject Name <span style='color:red'>*
                    <?php if(isset($error['subject_name'])) echo $error['subject_name'];
                ?>
                </span></label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php if(isset($subject_name)){ echo $subject_name;}?>" placeholder="Enter subject name" >
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form> 
    <!-- Subject table -->
    <h2 class="mt-5">SUBJECT</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SUBJECT Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($row=mysqli_fetch_assoc($result)){ ?>
        <tr>
            <td><?php echo $row['subject_name'];?></td>
            <td>
            <a href="subject.php?menu_id=<?= $menu_id;?>&edit_id=<?php echo $row['subject_id']; ?> " 
            class="btn btn-warning btn-sm">Edit</a>
            <a href="subject.php?menu_id=<?= $menu_id;?>&delete_id=<?php echo $row['subject_id']; ?>" 
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
