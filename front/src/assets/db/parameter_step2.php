<?php
// 允许上传的图片后缀
header('Access-Control-Allow-Methods:post');
header("Content-Type:text/html;charset=gb2312");
error_reporting(0);
$users = $_REQUEST['users'];
$project = $_REQUEST['project'];
$t1 = microtime(true);


include 'conn.php';
$sql = "SELECT * from project where id='".$project."' ;";
//echo $sql;
$step0 = mysqli_query($con,$sql); 
if($step0){
	$row=mysqli_fetch_array($step0,MYSQLI_ASSOC);  
	//print_r($row);
	$num=$row[node];
}else{
  die('{"success": "0"}');
} 

$sql = "SELECT * from node  where  node_project = '".$project."' order by CAST(node_id AS UNSIGNED) limit ".$num;
//echo "$sql<br>";
$step1 = mysqli_query($con,$sql); 
$step2 = mysqli_query($con,$sql); 
if($step1){
 while($rowt=mysqli_fetch_array($step1))  {
	$NH=$rowt[NH];
	$NZ=$rowt[NZ];
	$sql="update data set TH=(";
	$node_1_9 = explode(',',$rowt[node_1_9]); 
	$node_10_12 = explode(',',$rowt[node_10_12]); 
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
	$sql=$sql."0)/".$NZ." where project='".$project."' and node='".$rowt[node_id]."';"; 
	//echo $sql."<br>";
	$result1 = mysqli_query($con,$sql); 
  }
}else{
  die("fetch data failed!");
}

if($step2){
 while($row1=mysqli_fetch_array($step2))  {
	$r=$row1[r];
	$a=$row1[a];
	$E=$row1[E];
	$V=$row1[V];
	$U=$row1[U];
	$E1=$row1[E1];
	$V1=$row1[V1];
	$NH=$row1[NH];
	$NZ=$row1[NZ];
	$RR=$row1[RR];
	$M=$row1[M];
	$K0=$row1[K0];
	 //print_r($row1);
	$sql="update data set circ=((".$E."*(".TH."+3*".$V."*".TZ."))/(3*(".$M."-".$V."*".$V."))),axis=".TZ."*".$E."+(".$E."*".$U."*(".TH."+3*".$V."*".TZ."))/(3*(".$M."-".$V."*".$V.")) where project='".$project."' and node='".$row1[node_id]."';"; 
	//echo $sql."<br>";
	$result1 = mysqli_query($con,$sql); 
  }
}else{
  die("fetch data failed!");
}
	
echo '{"success": "1"}';
mysqli_close($con); 
$t2 = microtime(true);
//echo '耗时'.round($t2-$t1,3).'秒<br>';
//echo 'Now memory_get_usage: ' . memory_get_usage() . '<br />';
?>