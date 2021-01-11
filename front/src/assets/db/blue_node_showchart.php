<?php
$mac = strtolower($_GET['mac']);
$start = $_GET['st']; 
$end = $_GET['et'];  
include("conn.php"); 
$query="select * from data where mac='$mac' and timestamp between '$start 00:00:00' and '$end 23:59:59'  order by timestamp desc;";
//print_r($query);


	//$sql = 'use bluetooth';
	//mysqli_select_db(cz);
	//$result=mysqli_query($conn,$sql);
	//print_r($result);
	$result=mysqli_query($con,$query);
	if (!$result) {
		printf("Error: %s\n", mysqli_error($con));
    exit();
}
	$arrcz = array(); 
	while($row = mysqli_fetch_array($result)) { 
	  $count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
	  for($i=0;$i<$count;$i++){ 
		unset($row[$i]);//删除冗余数据 
	  } 
	 
	  array_push($arrcz,$row); 
	 
	} 
	//array_push($arrcz,",{'ttt':'1'}"); 
	$cz=json_encode($arrcz,JSON_UNESCAPED_UNICODE); 
	
	$f = 'mysql';

	print_r($cz);



//print_r(",{'ttt':'1'}");
//echo $f;



?>