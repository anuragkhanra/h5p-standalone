<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       anurag
 * @since      1.0.0
 *
 * @package    Wp_Stand
 * @subpackage Wp_Stand/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
$host = $_SERVER['HTTP_HOST'];
$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
// Process Map
if(isset($_POST['map_submit'])){
    $id = $_POST['id'];
    $course = $_POST['s_course'];
    $chapter = $_POST['s_chap'];
    $cdate = date("d.m.Y");
    $query = "INSERT INTO `c_map` (`wp_cont_id`,`crs_id`,`chp_id`,`map_date`) VALUES ('$id','$course','$chapter','$cdate')";
    mysqli_query($con,$query);
}
// Setup form
if(isset($_POST['setup'])){
    $sql = "CREATE TABLE `course` (
        `crs_id` int(11) NOT NULL,
        `crs_name` varchar(255) NOT NULL,
        `crs_date` text NOT NULL,
        `crs_user` varchar(255) NOT NULL,
        `crs_update` text NOT NULL,
        `crs_delete` tinyint(4) NOT NULL
      );";
    $sql .= "ALTER TABLE `course` ADD PRIMARY KEY (`crs_id`);";
    $sql .= "ALTER TABLE `course` MODIFY `crs_id` int(11) NOT NULL AUTO_INCREMENT;";
    $sql .= "CREATE TABLE `chapter` (
        `chp_id` int(11) NOT NULL,
        `crs_id` int(11) NOT NULL,
        `chp_name` varchar(255) NOT NULL,
        `chp_date` text NOT NULL,
        `chp_user` varchar(255) NOT NULL,
        `chp_update` text NOT NULL,
        `chp_delete` tinyint(4) NOT NULL
      );";
    $sql .= "ALTER TABLE `chapter` ADD PRIMARY KEY (`chp_id`);";
    $sql .= "ALTER TABLE `chapter` MODIFY `chp_id` int(11) NOT NULL AUTO_INCREMENT;";
    $sql .= "CREATE TABLE `c_map` (
        `wp_cont_id` int(11) NOT NULL,
        `crs_id` int(11) NOT NULL,
        `chp_id` int(11) NOT NULL,
        `map_date` text NOT NULL
      );";
    $con->multi_query($sql);
}
if(!mysqli_query($con,"SELECT * FROM `course`")){
?>
    <form align="center" action="" method="POST">
        <button id="but" name="setup" type="submit">Set Up</button>
    </form>
<?php } ?>
<html>
    <body>

    
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form method="POST" action="">
                <input type="hidden" name="id" id="input1">
                Select Course: 
                <select name="s_course" required>
                    <option value="">__SELECT__</option>
            <?php
            $result2 = mysqli_query($con,"SELECT * FROM `course`");
            while($row2 = mysqli_fetch_assoc($result2)){
                echo '<option value="'.$row2['crs_id'].'">'.$row2['crs_name'].'</option>';
            }
            ?>
                </select><br>
                Select Chapter:
                <select name="s_chap" required>
                    <option value="">__SELECT__</option>
            <?php
            $result3 = mysqli_query($con,"SELECT * FROM `chapter`");
            while($row3 = mysqli_fetch_assoc($result3)){
                echo '<option value="'.$row3['chp_id'].'">'.$row3['chp_name'].'</option>';
            }
            ?>
                </select><br>
                <input type="submit" name="map_submit" value="Process Map">
            </form>
        </div>
    </div>

    <div id="di">
        <?php
        //global $current_user;
        //get_currentuserinfo();
        //echo $current_user->user_login;
        
        // Chapter and Course Creation
        
            if(isset($_POST['submit_course'])){
                $course = stripslashes($_REQUEST['course']);
                $course = mysqli_real_escape_string($con,$course);
                global $current_user;
                wp_get_current_user();
                $user = $current_user->user_login;
                $cdate = $udate = date("d.m.Y");
                mysqli_query($con,"INSERT INTO `course` (`crs_name`,`crs_date`,`crs_user`,`crs_update`,`crs_delete`)
                 VALUES ('$course','$cdate','$user','$udate',1)");
            }

            if(isset($_POST['submit_chapter'])){
                $chapter = stripslashes($_REQUEST['chp']);
                $chapter = mysqli_real_escape_string($con,$chapter);
                global $current_user;
                get_currentuserinfo();
                $user = $current_user->user_login;
                $cdate = $udate = date("d.m.Y");
                $crs = $_REQUEST['sitem'];
                mysqli_query($con,"INSERT INTO `chapter` (`crs_id`,`chp_name`,`chp_date`,`chp_user`,`chp_update`,`chp_delete`) 
                VALUES ('$crs','$chapter','$cdate','$user','$udate',1)");
            }
        ?>
        <form action="" method="POST" id="f1">
            <input type="text" name="course" value="Course Name" required>
            <input type="submit" name="submit_course" value="New Course">
        </form>
        <form action="" method="POST" id="f2">
        <select name="sitem" required>
        <option value="">__SELECT__</option>
            <?php
            $result1 = mysqli_query($con,"SELECT * FROM `course`");
            while($row1 = mysqli_fetch_assoc($result1)){
                echo '<option value="'.$row1['crs_id'].'">'.$row1['crs_name'].'</option>';
            }
            ?>
        </select>
            <input type="text" name="chp" value="Chapter Name" required>
            <input type="submit" name="submit_chapter" value="New Chapter">
        </form>
    </div>

    <!-- unmapped Table -->

    <table border="2px" style="width:70%; text-align: center; margin: 35px">
        <thead><tr><th colspan="4" style="font-size:145%;font-weight: bold">Unmapped Contents</th></tr></thead>
        <tr style="font-size:125%;font-weight: bold">
            <td>ID</td><td>Title</td><td>Initials</td><td>Map</td>
        </tr>
<?php
require 'ex.php';
    $root_dir = $_SERVER['DOCUMENT_ROOT']."/wordpress/wp-content/plugins/wp-stand/admin/partials/" ;

    // Process form
    if(isset($_POST['submit_process'])){
        //download the package
        $slug = $_REQUEST['slug'];
        $id = $_REQUEST['id'];
        $url = 'http://'.$host.'/wordpress/wp-content/uploads/h5p/exports/'.$slug.'-'.$id.'.h5p'; 
        $file_name = basename($url); 
                if(file_put_contents( $file_name,file_get_contents($url))) { 
                    echo "File downloaded successfully";
                    rename($file_name,$root_dir."h5p/workspace/my.h5p");
                } 
                else { 
                    exit("File downloading failed"); 
               }

        //unzip the h5p package
        $zip = new ZipArchive;
        if ($zip->open($root_dir.'h5p/workspace/my.h5p') === TRUE) {
            $zip->extractTo($root_dir.'h5p/workspace/'.$slug);
            $zip->close();
            echo '  Successfully unzipped';
        } else {
            echo ' Unzipping Failed';
        }

        //delete the h5p package
        unlink($root_dir.'h5p/workspace/my.h5p');
        
        //create html file
        $rename = $root_dir."h5p/demo/".$slug.".html";
        $url1 = $root_dir.'h5p/demo/index.html';
        file_put_contents( $slug,file_get_contents($url1));
        rename($slug,$rename);

        $marker = "workspace";
        injectData($rename,'/'.$slug,465);
            echo '<br><h2>http://'.$host.'/wordpress/wp-content/plugins/wp-stand/admin/partials/h5p/demo/'.$slug.'.html</h2>';
        }

    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    $query1 = "SELECT wp_h5p_contents.id, wp_h5p_contents.title, wp_h5p_contents.slug FROM `wp_h5p_contents`
    INNER JOIN c_map ON wp_h5p_contents.id=c_map.wp_cont_id";
    $query = "SELECT id, title, slug FROM `wp_h5p_contents` WHERE id NOT IN (SELECT wp_cont_id FROM c_map)";
    $result = mysqli_query($con,$query);
    if(mysqli_num_rows($result)==0){
        echo '<tr><td colspan="6">No contents yet</td></tr>';
    }
    else{
        while($row = mysqli_fetch_assoc($result)){
            ?>
            <?php
            echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['slug']}</td></td>";
            ?>
                <?php
                    echo "<td><button onclick='myF(".$row['id'].")'>Map</button></td>";
                ?>
            <?php
        }
    }
?>

    </table>

    <!-- Mapped Table -->

    <table border="2px" style="width:70%; text-align: center; margin: 35px">
        <thead><tr><th colspan="4" style="font-size:145%;font-weight: bold">Mapped Contents</th></tr></thead>
        <tr style="font-size:125%;font-weight: bold">
            <td>ID</td><td>Title</td><td>Initials</td><td>Process</td>
        </tr>
<?php

    $query1 = "SELECT wp_h5p_contents.id, wp_h5p_contents.title, wp_h5p_contents.slug FROM `wp_h5p_contents`
    INNER JOIN c_map ON wp_h5p_contents.id=c_map.wp_cont_id";
    //$query = "SELECT id, title, slug FROM `wp_h5p_contents` WHERE id NOT IN (SELECT wp_cont_id FROM c_map)";
    $result = mysqli_query($con,$query1);
    if(mysqli_num_rows($result)==0){
        echo '<tr><td colspan="6">No contents yet</td></tr>';
    }
    else{
        while($row = mysqli_fetch_assoc($result)){
            ?>
            <form action="" method="POST">
            <?php
            echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['slug']}</td>
            <input type='hidden' name='id' value='".$row['id']."'>
            <input type='hidden' name='slug' value='".$row['slug']."'>
            <td><input type='submit' name='submit_process' value='Process'></td>";
            ?>
            </form>
            <?php
        }
    }
?>

    </table>
    
    <script>
    var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal
function myF(id){
    modal.style.display = "block";
    document.getElementById("input1").value = id;
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

    </script>
    </body>
</html>