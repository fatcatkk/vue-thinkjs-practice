<?php
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");
$father_url = $_REQUEST['father_url'];
$child_url = $_REQUEST['child_url'];
$node = $_REQUEST['node'];

include 'conn.php';

$sql = "SELECT * from data_original where users='".$father_url."' and project='".$child_url."' and node='".$node."' order by times asc";

$result = mysqli_query($con,$sql); 
 
$arr = array(); 
while($row = mysqli_fetch_array($result)) { 
  $count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
  for($i=0;$i<$count;$i++){ 
    unset($row[$i]);//删除冗余数据 
  }
	array_push($arr,$row); 
} 


echo json_encode($arr,JSON_UNESCAPED_UNICODE); 
mysqli_close($con); 
/////////////////////////////////////////////////////////////////////////////////
?>