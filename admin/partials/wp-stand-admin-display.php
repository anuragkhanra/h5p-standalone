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
<!DOCTYPE html>
<html>
    <body>
    <table border="2px" style="width:70%; text-align: center; margin: 35px">
        <tr style="font-size:125%;font-weight: bold">
            <td>ID</td><td>Title</td><td>Initials</td><td>Process</td>
        </tr>
<?php
require 'ex.php';
    $host = $_SERVER['HTTP_HOST'];
    $con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $root_dir = $_SERVER['DOCUMENT_ROOT']."/wordpress/wp-content/plugins/wp-stand/admin/partials/" ;
    if(isset($_POST['submit'])){
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

    $query = "SELECT id, title, slug FROM `wp_h5p_contents`";
    $result = mysqli_query($con,$query);
    if(mysqli_num_rows($result)==0){
        echo '<tr><td colspan="4">No contents yet</td></tr>';
    }
    else{
        while($row = mysqli_fetch_assoc($result)){
            ?>
            <form action="" method="POST">
            <?php
            echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['slug']}</td>
            <input type='hidden' name='id' value='".$row['id']."'>
            <input type='hidden' name='slug' value='".$row['slug']."'>
            <td><input type='submit' name='submit' value='Process'></td></tr>\n";
            ?>
            </form>
            <?php
        }
    }
?>

    </table>
    </body>
</html>