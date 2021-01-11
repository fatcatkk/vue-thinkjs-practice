<?php
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");
$father_url = $_REQUEST['father_url'];
$child_url = $_REQUEST['child_url'];
//error_reporting(E_ALL ^ E_NOTICE);
include 'conn.php';
$t1 = microtime(true);
$sql = "SELECT * from project where id='".$child_url."' ;";
$step0 = mysqli_query($con,$sql); 
if($step0){
	$row=mysqli_fetch_array($step0,MYSQLI_ASSOC);  
	$num=$row['node'];
}else{
  die('{"success": "0"}');
} 

$sql = "SELECT * from node  where  node_project = '".$child_url."' order by CAST(node_id AS UNSIGNED) limit ".$num;
$step1 = mysqli_query($con,$sql); 
$step2 = mysqli_query($con,$sql); 
if($step1){
 while($rowt=mysqli_fetch_array($step1))  {
	 if($rowt['newdata']==1)
	 {
		$NH=$rowt['NH'];
		$NZ=$rowt['NZ'];
		$sql="update data set TH=(";
		$node_1_9 = explode(',',$rowt['node_1_9']); 
		$node_10_12 = explode(',',$rowt['node_10_12']); 
		for($index=0;$index<count($node_1_9);$index++){ 
		$sql=$sql."c"; 
		$sql=$sql.$node_1_9[$index];
		$sql=$sql."+"; 
		}
		$sql=$sql."0)/".$NH." , TZ= ("; 
		for($index=0;$index<count($node_10_12);$index++){ 
		$sql=$sql."c"; 
		$sql=$sql.$node_10_12[$index];
		$sql=$sql."+"; 
		}
		$sql=$sql."0)/".$NZ." where project='".$child_url."' and node='".$rowt['node_id']."';"; 
		//echo $sql;
		$result1 = mysqli_query($con,$sql); 
		//echo "<br>";
	 }
  }
}else{
  die("fetch data failed!");
}
//echo "<br>";
if($step2){
 while($row1=mysqli_fetch_array($step2))  {
	 if($row1['newdata']==1)
	 {
			$r=$row1['r'];
			$a=$row1['a'];
			$E=$row1['E'];
			$V=$row1['V'];
			$U=$row1['U'];
			$E1=$row1['E1'];
			$V1=$row1['V1'];
			$NH=$row1['NH'];
			$NZ=$row1['NZ'];
			$RR=$row1['RR'];
			$M=$row1['M'];
			$K0=$row1['K0'];
			$sql="update data set circ=((".$E."*(TH+3*".$V."*TZ))/(3*(".$M."-".$V."*".$V."))),axis=TZ*".$E."+(".$E."*".$U."*(TH+3*".$V."*TZ))/(3*(".$M."-".$V."*".$V.")) where project='".$child_url."' and node='".$row1['node_id']."';"; 
			//echo $sql;
			$result1 = mysqli_query($con,$sql); 
			//echo "<br>";
	 }
	 
 }
}else{
  die("fetch data failed!");
}

$sql = "update node set newdata='0' where node_project='".$child_url."' ;";
$result = mysqli_query($con,$sql); 

$sql = "SELECT * from data where users='".$father_url."' and project='".$child_url."' order by times asc";

$result = mysqli_query($con,$sql); 
 
$arr = array(); 
while($row = mysqli_fetch_array($result)) { 
	//echo $row[data];

  $count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
  for($i=0;$i<$count;$i++){ 
    unset($row[$i]);//删除冗余数据 
  }
	array_push($arr,$row); 
} 


echo json_encode($arr,JSON_UNESCAPED_UNICODE); 
mysqli_close($con); 
$t2 = microtime(true);
//echo '耗时'.round($t2-$t1,3).'秒<br>';
/////////////////////////////////////////////////////////////////////////////////
?>