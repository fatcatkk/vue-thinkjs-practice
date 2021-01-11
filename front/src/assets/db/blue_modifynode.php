<?php
$mac= $_GET['mac'];
$x= $_GET['x'];
$y= $_GET['y'];
$z= $_GET['z'];
include 'conn.php';
$sql = "REPLACE INTO location (mac,x,y,z)VALUES('".$mac."','".$x."','".$y."','".$z."');";
//echo $sql;
$result1 = mysqli_query($con,$sql); 
//echo $result;


if($result1)//插入成功后返回true，失败返回false
{
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$memcachelife=120;


$query="(select (@i:=@i+1)as i,a.*,b.x,b.y,b.z from node_online as a left join location as b on a.mac=b.mac,(SELECT @i := 0) as cc)union(select (@i:=@i+1)as i,a.*,b.x,b.y,b.z from node_dropline as a left join location as b on a.mac=b.mac);";

				$keyblue='blue_get_list';
				include "conn.php";
                $result=mysqli_query($con,$query);
				$arrmqtt = array(); 
				while($row = mysqli_fetch_array($result)) { 
				  $count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
				  for($i=0;$i<$count;$i++){ 
					unset($row[$i]);//删除冗余数据 
				  } 
				   array_push($arrmqtt,$row); 
				} 
				$mqtt=json_encode($arrmqtt,JSON_UNESCAPED_UNICODE); 
                $redis->set($keyblue,$mqtt, $memcachelife);        //mysql 查询后，插入 memcached




 echo '{"success": "1"}'; 
}
else 
 echo '{"success": "0"}'; 
mysqli_close($con); 
?>