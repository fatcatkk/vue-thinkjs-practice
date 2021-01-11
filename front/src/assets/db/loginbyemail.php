<?php
//header("content-type:text/html;charset=utf-8");
$email= $_GET['email'];
$password= $_GET['password'];
include 'conn.php';
$sql = "SELECT token,uid from user where email='".$email."' and password='".$password."'";
//echo $sql;
$result = mysqli_query($con,$sql); 
$rs=mysqli_fetch_assoc($result);
$rs=json_encode($rs);
print_r($rs);
?>