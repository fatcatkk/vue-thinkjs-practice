<?php
//header("content-type:text/html;charset=utf-8");
//$token= $_GET['token'];
ini_set("display_errors", 0);

error_reporting(E_ALL ^ E_NOTICE);

error_reporting(E_ALL ^ E_WARNING);
$task_names= $_GET['task_names'];
$task_type= $_GET['task_type'];
$task_nodes=$_GET['task_nodes'];
$task_data=$_GET['task_data'];
$task_interval=$_GET['task_interval'];
$task_time=$_GET['task_time'];
$task_week=$_GET['task_week'];

include 'conn.php';
$sql="select task_names from task where task_names='".$task_names."';";
//echo $sql;
$result1 = mysqli_query($con,$sql); 

if($result1->num_rows<=0)//插入成功后返回true，失败返回false
{
	$sql = "INSERT INTO task (task_names,task_type,task_nodes,task_data,task_interval,task_time,task_week)  VALUES ('".$task_names."','".$task_type."','".$task_nodes."','".$task_data."','".$task_interval."','".$task_time."','".$task_week."');";
	//echo $sql;
	$result = mysqli_query($con,$sql); 
	//echo $result;
	if($result)//插入成功后返回true，失败返回false
	{
	 echo '{"success": "1"," task_names": "'. $task_names.'"}'; 
	}
	else 
	 echo '{"success": "0"}'; 
}
else 
 echo '{"success": "0"}'; 



//echo json_encode($arr,JSON_UNESCAPED_UNICODE); 
mysqli_close($con); 

//echo '[{"name": "data1","id": "1000002","channel": "3","update": "2019-1-30 11:30:30"}, {"name": "data2","id": "25","channel": "4","update": "2019-1-30 11:30:30"}, {"name": "data3","id": "30","channel":"5","update": "2019-1-30 11:30:30"}, {"name": "data4","id": "26","channel": "6","update": "2019-1-30 11:30:30"}]'
?>