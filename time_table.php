
<?php
include('db.php');
extract($_GET);
if(!isset($_GET['menu_id'])){
    header("Location: logout.php");
}
if(isset($delete_id)){
    mysqli_query($conn,"Delete from time_table where timetable_id=$delete_id");
    $msg['success']="This record has been deleted successfully";
}

if(isset($edit_id) AND $edit_id> 0){
$sql="Select * from time_table where timetable_id='".$edit_id."'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
    $row1=mysqli_fetch_assoc($result);
    $teacher_id=$row1['teacher_id'];
    $subj_of_class_id=$row1['subj_of_class_id'];
    $day=$row1['day'];
    $start_time=$row1['start_time'];
    $end_time=$row1['end_time'];
    }
}
extract($_POST);
if(isset($_POST['is_submit'])){
    // if(isset($is_submit))
    if(empty($teacher_id)) $error['teacher_id']="Required";
    if(empty($subj_of_class_id)) $error['subj_of_class_id']="Required";
    if(empty($day)) $error['day']="Required"; 
    if(empty($start_time)) $error['start_time']="Required";
    if(empty($end_time)) $error['end_time']="Required";
    if(empty($error)){
$duplicatecheckquery="  SELECT * from time_table
                                WHERE teacher_id='".$teacher_id."'
                                AND subj_of_class_id='".$subj_of_class_id."'
                                AND day='".$day."'
                                AND start_time='".$start_time."'
                                AND end_time='".$end_time."'";
    if(isset($edit_id)&& $edit_id>0){
            $duplicatecheckquery.= "AND timetable_id != '".$edit_id."'";
    } 
        // echo $duplicatecheckquery."";die;
        $result=mysqli_query($conn,$duplicatecheckquery);
        if(mysqli_num_rows($result)==0){
            if(isset($edit_id) && $edit_id>0){
                $sql="  update time_table SET 
                        teacher_id='".$teacher_id."',
                        subj_of_class_id='".$subj_of_class_id."',
                        day='".$day."',
                        start_time='".$start_time."',
                        end_time='".$end_time."'
                        where timetable_id='".$edit_id."'";
                $ok=mysqli_query($conn,$sql);
                if($ok){
                    $msg['success']="Record has been updated successfully";
                }
            }
            else{
                $sql="INSERT INTO time_table(teacher_id,subj_of_class_id,day,start_time,end_time)
                          VALUES('".$teacher_id."','".$subj_of_class_id."','".$day."','".$start_time."','".$end_time."')";
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
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject of teacher Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <?php include("nav_menu.php"); ?>
    <!-- Time table Form -->
    <div class="container mt-5">
        <h2 class="text-center">Time Table</h2>
        <div>
            <?php
             if(isset($error['msg']))  echo "<span style=color:red>".$error['msg']."</span>";
             else if(isset($msg['success']))  echo "<span style=color:green>".$msg['success']."</span>";
            ?>
        </div>
        <form action="" method="POST">
            <input type="hidden" value="Y" name="is_submit" /> 
            
         <div class="form-group">
            <label for="subj_of_class_id">
                Subject of class Name 
                <span style='color:red'>*
                    <?php if(isset($error['subj_of_class_id'])) echo $error['subj_of_class_id'];?>
                </span>
            </label>
            <br>
            <select name="subj_of_class_id"id="subj_of_class_id" class="form-control" >
                <option value= "">Select subject of class</option>
                <?php
                $sql = "SELECT soc.subj_of_class_id, s.subject_name, c.class_name 
                        FROM subject_of_class soc
                        INNER JOIN subject s ON soc.subject_id = s.subject_id
                        INNER JOIN class c ON soc.class_id = c.class_id";
                $result2 = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result2)) {?>
                    <option value='<?php echo $row['subj_of_class_id'];?>' <?php if(isset($subj_of_class_id) && $subj_of_class_id == $row['subj_of_class_id']) echo "selected"; ?> ><?php echo $row['class_name'].'_'.$row['subject_name'];?></option>
                <?php }?>
            </select> 
         </div>
         <div class="form-group">
                <label for="teacher_id">
                    Teacher Name 
                    <span style='color:red'>*
                        <?php if(isset($error['teacher_id'])) echo $error['teacher_id'];?>
                    </span>
                </label><br>
                <select name="teacher_id" id="teacher_id" class="form-control" >
                    <option value="">Select teacher</option>
                    <?php
                    // Fetch all subject from the database
                    $sql = "SELECT * FROM teacher";
                    $result1 = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result1)) {?>
                        <option value='<?php echo $row['teacher_id'];?>' <?php if(isset($teacher_id) && $teacher_id == $row['teacher_id']) echo "selected"; ?> ><?php echo $row['full_name'];?></option>
                    <?php }?>
            </select>
        </div> 
        <div class="form-group">
        <label for="day">
                    Day 
                    <span style='color:red'>*
                        <?php if(isset($error['day'])) echo $error['day'];?>
                    </span>
                </label><br>
                <select name="day" id="day" class="form-control" >
                <option value="">Select Day</option> 
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
                </select>
        </div >
        <div class="form-group">
        <label for="start_time">
                    Starting 
                    <span style='color:red'>*
                        <?php if(isset($error['start_time'])) echo $error['start_time'];?>
                    </span>
                </label><br>
        <input type="time" class="form-control" id="start_time" name="start_time" value="<?php if(isset($start_time)){ echo $start_time;}?>" >
        </div>
        <div class="form-group">
        <label for="end_time">
                    Ending 
                    <span style='color:red'>*
                        <?php if(isset($error['end_time'])) echo $error['end_time'];?>
                    </span>
                </label><br>
        <input type="time" class="form-control" id="end_time" name="end_time" value="<?php if(isset($end_time)){ echo $end_time;}?>" >
        </div>
        <button type="submit" class="btn btn-primary btn-block">Submit</button>
        </form> 
    <!--  table -->
    <h2 class="mt-5">Time Table</h2>
    <table class="table table-bordered">
        <thead>     
            <tr>
                <th>subTeacher</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Day</th>
                <th>Start Day</th>
                <th>End Day</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql    = "SELECT  time_table.*, teacher.full_name AS teacher_full_name, class.class_name, subject.subject_name
                        FROM time_table
                        LEFT JOIN teacher ON teacher.teacher_id = time_table.teacher_id
                        LEFT JOIN subject_of_class ON subject_of_class.subj_of_class_id = time_table.subj_of_class_id
                        LEFT JOIN class ON class.class_id = subject_of_class.class_id
                        LEFT JOIN subject ON subject.subject_id = subject_of_class.subject_id ";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){ ?>
                <tr>
                    <td><?php echo $row['teacher_full_name'];?></td>
                    <td><?php echo $row['class_name'];?></td>
                    <td><?php echo $row['subject_name'];?></td>
                    <td><?php echo $row['day'];?></td>
                    <td><?php echo $row['start_time'];?></td>
                    <td><?php echo $row['end_time'];?></td>
                     <td>
                        <a href="time_table.php?menu_id=<?= $menu_id;?>&edit_id=<?php echo $row['timetable_id']; ?> " class="btn btn-warning btn-sm">Edit</a>
                        <a href="time_table.php?menu_id=<?= $menu_id;?>&delete_id=<?php echo $row['timetable_id']; ?>"  class="btn btn-danger btn-sm" onclick="return confirm('Are u sure u want to delete this record?');">Delete</a>  
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