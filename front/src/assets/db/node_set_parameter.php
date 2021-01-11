<?php
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");
$father_url = $_REQUEST['father_url'];
$child_url = $_REQUEST['child_url'];
$node = $_REQUEST['node'];
$r = $_REQUEST['r'];
$a = $_REQUEST['a'];
$E = $_REQUEST['E'];
$V = $_REQUEST['V'];
$U = $_REQUEST['U'];
$E1 = $_REQUEST['E1'];
$V1 = $_REQUEST['V1'];
$NH = $_REQUEST['NH'];
$NZ = $_REQUEST['NZ'];



if(isset($_REQUEST["r"]) && !empty($_REQUEST["a"])&& isset($_REQUEST["E"]) && !empty($_REQUEST["V"]))
{
	include 'conn.php';

	if($r<$a)
		die ('{"success": "0"}');
	$R=3*$r;
	$K0=(-2)/(($E/$E1)*($V1-(($r*$r+$a*$a)/($r*$r-$a*$a)))-(1+$V));
	$M=($R*$R)/($R*$R-$r*$r-$K0*$r*$r);
	
	$sql = "UPDATE node SET r='".$r."',a='".$a."',E='".$E."',V='".$V."',U='".$U."',E1='".$E1."',V1='".$V1."',NH='".$NH."',NZ='".$NZ."',RR='".$R."',K0='".$K0."',M='".$M."',newdata = '1' where node_user='".$father_url."' and node_project='".$child_url."' and node_id='".$node."'";
	$result = mysqli_query($con,$sql); 
	mysqli_close($con); 
	echo '{"success": "1"}'; 
}
else
	echo '{"success": "0"}'; 
/////////////////////////////////////////////////////////////////////////////////
?>