<?php
 error_reporting(E_ALL || ~E_NOTICE); 
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");

$mac = $_REQUEST['mac'];
/////////////////////////////////////////////////////////////////////////////////

include 'conn.php';

if(isset($_REQUEST["mac"]))
{

	$sql = "INSERT ignore  INTO whitelist(mac) (select mac from (select * from data order by Id desc) `temp` where mac='".$mac."' group by mac order by Id desc) ;";
	$result = mysqli_query($con,$sql); 
	echo '{"success": "1"}'; 
}
else
	echo '{"success": "0"}'; 




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


$query="select Id,upper(mac)as mac,conv( major, 16, 10 )as major,conv( minor, 16, 10 )as minor,type,upper(uuid)as uuid,name,timestamp from (select * from (select * from data order by Id desc) `temp`  group by mac order by Id desc) as bb LEFT JOIN (SELECT mac as i FROM whitelist) as aa ON bb.mac=aa.i where aa.i IS NULL;";

				$keyblue='blue_get_Nulllist';
				if(!$redis->get($keyblue))

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


mysqli_close($con); 

?>