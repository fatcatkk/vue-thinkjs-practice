<?php
//header("content-type:text/html;charset=utf-8");
$token= $_GET['token'];
$project_id= $_GET['project_id'];
include 'conn.php';

	$sql = "DELETE FROM project WHERE id='".$project_id."' and token='".$token."' and locked='0';";
	$result = mysqli_query($con,$sql); 
	//echo $sql;
	$rc = mysqli_affected_rows($con);
	if($rc)//插入成功后返回true，失败返回false
	{
	 echo '{"success": "1"}'; 
	}
	else 
	 echo '{"success": "0"}'; 

mysqli_close($con); 
?>