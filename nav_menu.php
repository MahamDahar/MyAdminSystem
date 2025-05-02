<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">School System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">

                <?php
                // SQL query to fetch details 
                $sql = "SELECT * FROM `menus` ORDER BY sort_by";
                // Execute the query
                $result1 = mysqli_query($conn, $sql);
                // Loop through each row of the result
                while ($row1 = mysqli_fetch_assoc($result1)) {
                    $menu_id_nav = $row1['menu_id'];
                    $sql = "SELECT * FROM permissions a
                            INNER JOIN user_login b ON b.role_id = a.role_id
                            WHERE a.menu_id = '".$menu_id_nav."'
                            AND b.`id` = '".$_SESSION['user_id']."'";
                    $result2 = mysqli_query($conn, $sql);
                    $count2 = mysqli_num_rows($result2);
                    if(isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'SuperAdmin'){
                        $count2 = 1;
                    } 
                    if($count2 > 0){ ?> 
                        <li class="nav-item active">
                            <a class="<?php echo $row1['menu_class'];?>" href="<?php echo $row1['menu_link'];?>?menu_id=<?php echo $menu_id_nav;?>"><?php echo $row1['menu_name'];?></a>
                        </li>
                    <?php 
                    
                    }
                }?> 
            </ul>
        </div>
    </nav>