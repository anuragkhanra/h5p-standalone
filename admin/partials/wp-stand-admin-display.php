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
        <?php
        if(isset($_POST['submit_map'])){
            $wp_cont_id = $_POST['wp_id'];
            $crs_id = $_POST['crs_id'];
            $chp_id = $_POST['chp_id'];
            $cdate = date("d.m.Y");
            mysqli_query($con,"INSERT INTO `c_map`(`wp_cont_id`, `crs_id`, `chp_id`, `map_date`) VALUES
            ('$wp_cont_id','$crs_id','$chp_id','$cdate')");
        }
        ?>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            Select Course:
            <select id="course" required>
                <option value="" selected="selected" autocomplete="off">__SELECT__</option>
                <?php
            $result2 = mysqli_query($con,"SELECT * FROM `course`");
            while($row2 = mysqli_fetch_assoc($result2)){
                echo '<option value="'.$row2['crs_id'].'">'.$row2['crs_name'].'</option>';
            }
            ?>
            </select><br>
            <form action="" method="POST">
            Select Chapter:
            <select name="chp_id" id="chapter" required>
                <option value="">__SELECT__</option>
            </select>
            <input type="hidden" name="wp_id" id="input1">
            <input type="hidden" name="crs_id" id="tex"><br>
            <input id="but1" type="submit" value="Process Map" name="submit_map">
            </form>
        </div>
    </div>

    <script>
    document.getElementById("course").onchange = function() {showHint()};
    function showHint() {
        str = document.getElementById("course").value;
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("chapter").options.length = 0;
                var myArr = JSON.parse(this.responseText);
                for(var i = 0; i<myArr.length-1; i+=2){
                    var option = document.createElement("option");
                    option.text = myArr[i+1];
                    option.value = myArr[i];
                    var select = document.getElementById("chapter");
                    select.appendChild(option);
                }
                document.getElementById("tex").value = myArr[myArr.length-1];
            }
        };
        xmlhttp.open("GET", "http://"+window.location.hostname+"/wordpress/wp-content/plugins/wp-stand/admin/partials/ajax.php?q=" + str, true);
        xmlhttp.send();
}
    </script>

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
            $zip->extractTo($root_dir.'h5p/workspace/'.$id.'-'.$slug);
            $zip->close();
            echo '  Successfully unzipped';
        } else {
            echo ' Unzipping Failed';
        }

        //delete the h5p package
        unlink($root_dir.'h5p/workspace/my.h5p');
        
        //create html file
        $rename = $root_dir."h5p/demo/".$id.'-'.$slug.".html";
        $url1 = $root_dir.'h5p/demo/index.html';
        file_put_contents( $slug,file_get_contents($url1));
        rename($slug,$rename);

        $marker = "workspace";
        injectData($rename,'/'.$id.'-'.$slug,465);
            echo '<br><h2>http://'.$host.'/wordpress/wp-content/plugins/wp-stand/admin/partials/h5p/demo/'.$id.'-'.$slug.'.html</h2>';
        
        // copy the html file to workspace
        copy($root_dir."h5p/demo/".$id.'-'.$slug.".html",$root_dir."h5p/workspace/".$id.'-'.$slug."/index.html",);

        // Create the zip for export
        $dir = $root_dir."h5p/workspace/".$id."-".$slug."/";
        $zip = "my.zip";
        $fpath = $path.$zip;
        

        // Get real path for our folder
$rootPath = realpath($dir);

// Initialize archive object
$zip = new ZipArchive();
$zip->open($root_dir."h5p/workspace/".$id."-".$slug."/".$id."-".$slug.".zip", ZipArchive::CREATE | ZipArchive::OVERWRITE);

// Create recursive directory iterator
/** @var SplFileInfo[] $files */
$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
        // Get real and relative path for current file
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);

        // Add current file to archive
        $zip->addFile($filePath, $relativePath);
    }
}

// Zip archive will be created only after closing object
$zip->close();
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
    <?php
    $cou = mysqli_query($con,"SELECT COUNT(crs_id) FROM `course`");
    $cou = mysqli_fetch_array($cou);
    $chp = mysqli_query($con,"SELECT COUNT(chp_id) FROM `chapter`");
    $chp = mysqli_fetch_array($chp);
    //$first = mysqli_fetch_array(mysqli_query($con,"SELECT course.crs_name FROM course INNER JOIN c_map ON course.crs_id=c_map.crs_id"));
    $first = mysqli_fetch_all(mysqli_query($con,"SELECT * FROM `course`"),MYSQLI_ASSOC);
    for($i=1;$i<=$cou[0];$i++){
        if(mysqli_num_rows(mysqli_query($con,"SELECT crs_id FROM c_map WHERE crs_id=$i")) != 0){
        ?>
        <table border="2px" style="width:70%; text-align: center; margin: 35px">
        <form action="" method="POST">
        <?php
        echo "<tr><td style='font-size:125%;font-weight: bold'>".$first[$i-1]['crs_name']."</td></tr>";
        echo "<tr style='font-size:125%;font-weight: bold'>
        <td>Title</td><td>Course</td><td>Chapter</td><td>Process</td><td>Download</td>
    </tr>";
        for($j=1;$j<=$chp[0];$j++){
            $temp = mysqli_query($con,"SELECT wp_h5p_contents.id,wp_h5p_contents.title, wp_h5p_contents.slug, course.crs_name, 
            chapter.chp_name FROM c_map,wp_h5p_contents,course,chapter WHERE wp_h5p_contents.id=c_map.wp_cont_id
            AND course.crs_id=c_map.crs_id AND chapter.chp_id=c_map.chp_id AND c_map.crs_id=$i AND c_map.chp_id=$j");
            while($row = mysqli_fetch_assoc($temp)){
                $down = "../wp-content/plugins/wp-stand/admin/partials/h5p/workspace/".$row['id']."-".$row['slug'].
            "/".$row['id']."-".$row['slug'].".zip";
                echo "<tr><td>{$row['title']}</td><td>
                {$row['crs_name']}</td><td>{$row['chp_name']}</td>
                <input type='hidden' name='id' value='".$row['id']."'>
            <input type='hidden' name='slug' value='".$row['slug']."'>
            <td><input type='submit' name='submit_process' value='Process'></td></form>
            <td><a href='{$down}' download>
            <input type='button' value='Zip Download'>
            </a></td></tr>";        
            } 
        }
        echo "</table>";
    }
    }
    ?>
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