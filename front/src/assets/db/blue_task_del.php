<?php
//header("content-type:text/html;charset=utf-8");
$token= $_GET['token'];
$task_id= $_GET['task_id'];
include 'conn.php';

	$sql = "DELETE FROM task WHERE id='".$task_id."' ;";
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