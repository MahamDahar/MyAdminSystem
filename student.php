<?php
include('db.php');
extract($_GET);
if(!isset($_GET['menu_id'])){
    header("Location: logout.php");
}
if(isset($delete_id)){
    mysqli_query($conn,"Delete from student where student_id=$delete_id");
    $msg['success']="This record has been deleted successfully";
}

if(isset($edit_id) AND $edit_id> 0){
$sql="Select * from student where student_id='".$edit_id."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
    $row1=mysqli_fetch_assoc($result);
    $full_name=$row1['full_name'];
    $address=$row1['address'];
    $phone_no=$row1['phone_no'];
    }
}
extract($_POST);
if(isset($_POST['is_submit'])){
    // if(isset($is_submit))
    if(empty($full_name)) $error['full_name']="Required";
    if(empty($address)) $error['address']="Required";
    if(empty($phone_no) || $phone_no==0) $error['phone_no']="Required";
    if(empty($error)){
$duplicatecheckquery="  SELECT * from student 
                                WHERE full_name='".$full_name."'
                                AND address='".$address."'
                                AND phone_no='".$phone_no."'";
    if(isset($edit_id)&& $edit_id>0){
            $duplicatecheckquery.= "AND student_id != '".$edit_id."'";
    } 
        // echo $duplicatecheckquery."";die;
        $result=mysqli_query($conn,$duplicatecheckquery);
        if(mysqli_num_rows($result)==0){
            if(isset($edit_id) && $edit_id>0){
                $sql="  update student SET 
                        full_name='".$full_name."',
                        address='".$address."',
                        phone_no='".$phone_no."'
                        where student_id='".$edit_id."'";
                $ok=mysqli_query($conn,$sql);
                if($ok){
                    $msg['success']="Record has been updated successfully";
                }
            }
            else{
                $sql="INSERT INTO student(full_name,phone_no,address)
                          VALUES('".$full_name."','".$phone_no."','".$address."')";
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
$result=mysqli_query($conn,"Select * from student");
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
    <!-- Student Form -->
    <div class="container mt-5">
        <h2 class="text-center">STUDENT FORM</h2>
        <div>
            <?php
             if(isset($error['msg']))  echo "<span style=color:red>".$error['msg']."</span>";
             else if(isset($msg['success']))  echo "<span style=color:green>".$msg['success']."</span>";
            ?>
        </div>
        <form action="" method="POST">
            <input type="hidden" value="Y" name="is_submit" /> 
            <div class="form-group">
                <label for="full_name">Full Name <span style='color:red'>*
                    <?php if(isset($error['full_name'])) echo $error['full_name'];
                ?>
                </span></label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php if(isset($full_name)){ echo $full_name;}?>" placeholder="Enter student Name" >
            </div>
            <div class="form-group">
                <label for="address">Address <span style=color:red>*
               <?php if(isset($error['address'])){echo $error['address']; }
               ?>
                </span></label>
                <input type="text" class="form-control" id="address" name="address" value="<?php if(isset($address)){ echo $address;}?>"
                placeholder="Enter address" >
            </div>
            <div class="form-group">
                <label for="phone_no">Phone_no <span style=color:red>*
                 <?php if(isset($error['phone_no'])){ echo $error['phone_no']; }
                  ?>   
                <span></label>
                <input type="number" class="form-control" id="phone_no" name="phone_no" value="<?php if(isset($phone_no)){ echo $phone_no;}?>" 
                placeholder="Enter phone_no" >
            </div>
            <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form> 
    <!-- Student table -->
    <h2 class="mt-5">student</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student name</th>
                <th>Address</th>
                <th>Phone_no</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($row=mysqli_fetch_assoc($result)){ ?>
        <tr>
            <td><?php echo $row['full_name'];?></td>
            <td><?php echo $row['address'];?></td>
            <td><?php echo $row['phone_no'];?></td>
            <td>
            <a href="student.php?edit_id=<?php echo $row['student_id']; ?> " 
            class="btn btn-warning btn-sm">Edit</a>
            <a href="student.php?delete_id=<?php echo $row['student_id']; ?>" 
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
