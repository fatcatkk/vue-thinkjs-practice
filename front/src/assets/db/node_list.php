<?php
 
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");

$father_url = $_REQUEST['father_url'];
$child_url = $_REQUEST['child_url'];
/////////////////////////////////////////////////////////////////////////////////

include 'conn.php';
$sql = "SELECT node from project  where token='".$father_url ."' and id = '".$child_url."'limit 1";

$result = mysqli_query($con,$sql); 
$row = mysqli_fetch_array($result);
$sum =$row[0];

$sql = "SELECT * from node where node_user='".$father_url ."' and node_project = '".$child_url."' order by CAST(node_id AS UNSIGNED) limit ".$sum;
//echo $sql;
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

?>