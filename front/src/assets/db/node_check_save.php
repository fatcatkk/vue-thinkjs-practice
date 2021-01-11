<?php
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");
$father_url = $_REQUEST['father_url'];
$child_url = $_REQUEST['child_url'];
$node = $_REQUEST['node'];

if(isset($_REQUEST["ch1_9"]) && !empty($_REQUEST["ch1_9"])&& isset($_REQUEST["ch10_12"]) && !empty($_REQUEST["ch10_12"]))
{
	$ch1_9= $_REQUEST['ch1_9'];
	//echo implode(",",$ch1_9);
	$ch10_12= $_REQUEST['ch10_12'];
	//echo implode(",",$ch10_12);

	include 'conn.php';

	$sql = "UPDATE node SET node_1_9='".implode(",",$ch1_9)."',node_10_12='".implode(",",$ch10_12)."',newdata = '1' where node_user='".$father_url."' and node_project='".$child_url."' and node_id='".$node."'";
	//echo $sql;
	//echo $sql;
	$result = mysqli_query($con,$sql); 

	mysqli_close($con); 
	echo '{"success": "1"}'; 
}
else
	echo '{"success": "0"}'; 
/////////////////////////////////////////////////////////////////////////////////
?>