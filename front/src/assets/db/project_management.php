<?php
//header("content-type:text/html;charset=utf-8");
$token= $_GET['token'];
include 'conn.php';
$sql = "SELECT * from project where  token='".$token."'";
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

//echo '[{"name": "data1","id": "1000002","channel": "3","update": "2019-1-30 11:30:30"}, {"name": "data2","id": "25","channel": "4","update": "2019-1-30 11:30:30"}, {"name": "data3","id": "30","channel":"5","update": "2019-1-30 11:30:30"}, {"name": "data4","id": "26","channel": "6","update": "2019-1-30 11:30:30"}]'
?>