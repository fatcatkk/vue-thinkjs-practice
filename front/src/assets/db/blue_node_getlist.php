<?php
 
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");
$build = $_GET['build']; 
error_reporting(E_ALL || ~E_NOTICE); 
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$memcachelife=10;


//$query="(select (@i:=@i+1)as i,a.*,b.x,b.y,b.z from node_online as a left join location as b on a.mac=b.mac,(SELECT @i := 0) as cc)union(select (@i:=@i+1)as i,a.*,b.x,b.y,b.z from node_dropline as a left join location as b on a.mac=b.mac);";
$query="select (@i:=@i+1)as i,id,x,y,z,type,name from location,(SELECT @i:=0) as i  where build='$build';";
$keyblue='blue_get_list_build'.$build;
if(!$redis->get($keyblue))
{
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
				print_r($mqtt);
				$con->close(); //关闭连接
}	
else{
		//echo "rrr";
        $data_mem=$redis->get($keyblue);
		print_r($data_mem);
		
}



?>