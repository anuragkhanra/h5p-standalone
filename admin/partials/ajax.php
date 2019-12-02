<?php
require '../../../../../wp-config.php';
$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$course = $_REQUEST['q'];
//$chapter['__SELECT__'] = 0;
$chapter = array("","__SELECT__");
$result = mysqli_query($con,"SELECT * FROM `chapter` WHERE `crs_id`=$course");
while($row = mysqli_fetch_assoc($result)){
    //$chapter += [$row['chp_name']=>$row['chp_id']];
    array_push($chapter,$row['chp_id'],$row['chp_name']);
}
array_push($chapter,$course);
echo json_encode($chapter);
?>