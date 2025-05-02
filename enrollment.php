<?php
include('db.php');
extract($_GET);
if(!isset($_GET['menu_id'])){
    header("Location: logout.php");
}
// DELETE logic
if (isset($delete_id)){
    mysqli_query($conn, "DELETE FROM enrollment WHERE enrollment_id=$delete_id");
    $msg['success'] = "This record has been deleted successfully";
}
// EDIT logic
if (isset($edit_id) && $edit_id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM enrollment WHERE enrollment_id='$edit_id'");
    if (mysqli_num_rows($result) > 0) {
        $row1 = mysqli_fetch_assoc($result);
        $student_id = $row1['student_id'];
        $class_id = $row1['class_id'];
        $year = $row1['year'];
    }
}

// FORM submission
if (isset($_POST['is_submit'])) {
    extract($_POST);

    // Validation
    if (empty($student_id)) $error['student_id'] = "Required";
    if (empty($class_id)) $error['class_id'] = "Required";
    if (empty($year)) $error['year'] = "Required";

    if (empty($error)) {
        // Check for duplicate
        $duplicateQuery = "SELECT * FROM enrollment WHERE 
                        student_id='$student_id' 
                        AND class_id='$class_id'
                        AND year='$year'";
        if (isset($edit_id) && $edit_id > 0) {
            $duplicateQuery .= " AND enrollment_id != '$edit_id'";
        }

        $result = mysqli_query($conn, $duplicateQuery);
        if (mysqli_num_rows($result) == 0) {
            if (isset($edit_id) && $edit_id > 0) {
                // Update
                $sql = "UPDATE enrollment SET student_id='$student_id', class_id='$class_id', year='$year' WHERE enrollment_id='$edit_id'";
                $ok = mysqli_query($conn, $sql);
                if ($ok) $msg['success'] = "Record has been updated successfully";
            } else {
                // Insert
                $sql = "INSERT INTO enrollment(student_id, class_id, year) VALUES('$student_id', '$class_id', '$year')";
                $ok = mysqli_query($conn, $sql);
                if ($ok) $msg['success'] = "Record has been added successfully";
            }
        } else {
            $error['msg'] = "This record already exists.";
        }
    }
} 
// Fetch all enrollments with join to show names
$sql = "SELECT e.*, s.full_name, c.class_name 
        FROM enrollment e 
        JOIN student s ON e.student_id = s.student_id 
        JOIN class c ON e.class_id = c.class_id";
//echo $sql;
// echo "<br><br><br><br><br>aaaaaaaaaaaaaaaaaatest".date('YmdHis');die;
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>School system</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include("nav_menu.php"); ?>
<div class="container mt-5">
    <h2 class="text-center">Enrollment</h2>
    <div>
        <?php
        if (isset($error['msg'])) echo "<span style='color:red'>{$error['msg']}</span>";
        elseif (isset($msg['success'])) echo "<span style='color:green'>{$msg['success']}</span>";
        ?>
    </div>
    <form method="POST">
        <input type="hidden" name="is_submit" value="Y">
        
        <div class="form-group">
            <label>Class <span style="color:red">*</span></label>
            <select name="class_id" class="form-control">
                <option value="">Select Class</option>
                <?php
                $classResult = mysqli_query($conn, "SELECT * FROM class");
                while ($row = mysqli_fetch_assoc($classResult)) {
                    $selected = isset($class_id) && $class_id == $row['class_id'] ? 'selected' : '';
                    echo "<option value='{$row['class_id']}' $selected>{$row['class_name']}</option>";
                }
                ?>
            </select>
            <?php if (isset($error['class_id'])) echo "<span style='color:red'>{$error['class_id']}</span>"; ?>
        </div>

        <div class="form-group">
            <label>Student <span style="color:red">*</span></label>
            <select name="student_id" class="form-control">
                <option value="">Select Student</option>
                <?php
                $studentResult = mysqli_query($conn, "SELECT * FROM student");
                while ($row = mysqli_fetch_assoc($studentResult)) {
                    $selected = isset($student_id) && $student_id == $row['student_id'] ? 'selected' : '';
                    echo "<option value='{$row['student_id']}' $selected>{$row['full_name']}</option>";
                }
                ?>
            </select>
            <?php if (isset($error['student_id'])) echo "<span style='color:red'>{$error['student_id']}</span>"; ?>
        </div>

        <div class="form-group">
            <label>Year <span style="color:red">*</span></label>
            <input type="text" name="year" class="form-control" value="<?php echo isset($year) ? $year : ''; ?>">
            <?php if (isset($error['year'])) echo "<span style='color:red'>{$error['year']}</span>"; ?>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Submit</button>
    </form>

    <!-- Table -->
    <h2 class="mt-5">Enrollment Records</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Class Name</th>
            <th>Student Name</th>
            <th>Year</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['class_name']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['year']; ?></td>
                <td>
                    <a href="enrollment.php?menu_id=<?=$menu_id;?>&edit_id=<?php echo $row['enrollment_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="enrollment.php?menu_id=<?=$menu_id;?>&delete_id=<?php echo $row['enrollment_id']; ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<footer class="bg-dark text-white text-center py-3">
    <p>&copy; 2025 School System. All rights reserved.</p>
</footer>
</body>
</html>
