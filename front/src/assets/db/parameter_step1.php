<?php
// 允许上传的图片后缀
header('Access-Control-Allow-Methods:post');
header("Content-Type:text/html;charset=gb2312");
$project = $_REQUEST['project'];
$r = floatval($_REQUEST['r']);
$a = floatval($_REQUEST['a']);
$E = floatval($_REQUEST['E']);
$V = floatval($_REQUEST['V']);
$U = floatval($_REQUEST['U']);
$E1 = floatval($_REQUEST['E1']);
$V1 = floatval($_REQUEST['V1']);
$NH = floatval($_REQUEST['NH']);
$NZ = floatval($_REQUEST['NZ']);
if(($r-$a)==0)
die ('{"success": "0"}');
$R=3*$r;
$K0=(-2)/(($E/$E1)*($V1-(($r*$r+$a*$a)/($r*$r-$a*$a)))-(1+$V));
$M=($R*$R)/($R*$R-$r*$r-$K0*$r*$r);
//echo $R.'<br>';
//echo $K0.'<br>';
//echo $M.'<br>';
include 'conn.php';

$sql = "REPLACE INTO parameter(r,a,E,V,U,E1,V1,NH,NZ,RR,K0,M,project_id) VALUE('".$r."','".$a."','".$E."','".$V."','".$U."','".$E1."','".$V1."','".$NH."','".$NZ."','".$R."','".$K0."','".$M."','".$project."');";


//replace into parameter(r,a,E,V,E1,V1,RR,K0,M) values($r,$a,$E,$V,$E1,$V1,$R,$K0,$M);";
//echo $sql;
$result = mysqli_query($con,$sql); 
echo '{"success": "1"}';
mysqli_close($con); 
?>