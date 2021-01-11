<?php
//header("content-type:text/html;charset=utf-8");
//echo $_POST["email"];//可以输出form中标签的属性值   其中参数的name
//echo $_POST["password"];
$token= $_GET['token'];
include 'conn.php';
$sql = "SELECT * from token where token='".$token."'";
//echo $sql;
$result = mysqli_query($con,$sql); 
$rs=mysqli_fetch_assoc($result);
$rs=json_encode($rs);
print_r($rs);

?>