<?php 
header("content-Type: text/html; charset=utf-8");//字符编码设置 
$servername = "127.0.0.1"; 
$username = "root"; 
$password = "153924"; 
$dbname = "metro";
// 创建连接 
$con =mysqli_connect($servername, $username, $password, $dbname); 
// 检测连接 
if (mysqli_connect_errno()) 
{ 
  echo "Failed to connect to MySQL: " . mysqli_connect_error(); 
} 
mysqli_query($con,'set names utf8');  
?>