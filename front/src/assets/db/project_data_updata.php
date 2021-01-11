<?php 
error_reporting( E_ALL&~E_NOTICE );
$father_url= $_GET['father_url'];
$child_url= $_GET['child_url'];
$node= $_GET['node'];
$file= $_GET['file'];
$dir = iconv("UTF-8", "GBK", "upload/".$father_url."/". $child_url."/".$file);
 
include 'conn.php';    
//将文件一次性全部读出来
$excelData = array();
$content = trim(file_get_contents($dir));
$excelData = explode("\r",$content);
$sum= count($excelData);
$chunkData = array_chunk($excelData, 5000); // 将这个10W+ 的数组分割成5000一个的小数组。这样就一次批量插入5000条数据。mysql 是支持的。
//print_r($chunkData);
$count = count($chunkData);
$t1 = microtime(true);
for ($i = 0; $i < $count; $i++) {
	
	$insertRows = array();
	foreach($chunkData[$i] as $value){
		$string = mb_convert_encoding(trim(strip_tags($value)), 'utf-8', 'gbk');//转码
		$v = explode(' ', trim($string));
		//print_r($v);
		$row = array();
		$row['1']    = empty($v[0].' '.$v[1]) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',strtotime($v[0].' '.$v[1]));
		$row['2'] = $v[2];
		$row['3']  = $v[3];
		$row['4'] = $v[4];
		$row['5']   = $v[5];
		$row['6']    = $v[6];
		$row['7']	 = $v[7];
		$row['8']    = $v[8];
		$row['9']    = $v[9];
		$row['10']    = $v[10];
		$row['11']    = $v[11];
		$row['12']    = $v[12];
		$row['13']    = $v[13];
		$row['14']    = $v[14];
		$row['15']    = $v[15];
		$row['16']    = $father_url;
		$row['17']    = $child_url;
		$row['18']    = $node;
		$sqlString       = '('."'".implode( "','", $row ) . "'".')'; //批量
		$insertRows[]    = $sqlString;
		//echo " <br>//////////////////44/////////////////// <br>";
	}
	//print_r($insertRows);
	$result = addDetail($con,$insertRows,$row['16'],$row['17'],$row['18']); //批量将sql插入数据库。
}
//$t2 = microtime(true);
//echo '<br>耗时'.round($t2-$t1,3).'秒<br>';
$sqlzero_0 ="DELETE FROM data where users='".$users."' and project='".$project."' and node='".$node."';";
$resultzero_0 = mysqli_query($con,$sqlzero_0);
$sqlzero_1 ="select *  from data_original where users='".$father_url."' and project='".$child_url."' and node='".$node."' order by times asc limit 1;";
//echo $sqlzero_1;
$resultzero_1 = mysqli_query($con,$sqlzero_1);
$rowzero_1 = mysqli_fetch_array($resultzero_1);
$c1=$rowzero_1[c1];
$c2=$rowzero_1[c2];
$c3=$rowzero_1[c3];
$c4=$rowzero_1[c4];
$c5=$rowzero_1[c5];
$c6=$rowzero_1[c6];
$c7=$rowzero_1[c7];
$c8=$rowzero_1[c8];
$c9=$rowzero_1[c9];
$c10=$rowzero_1[c10];
$c11=$rowzero_1[c11];
$c12=$rowzero_1[c12];
$c13=$rowzero_1[c13];
$c14=$rowzero_1[c14];

$sqlzero_2 ="insert into data(times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node) select times,c1-".$c1." as c1,c2-".$c2." as c2,c3-".$c3." as c3,c4-".$c4." as c4,c5-".$c5." as c5,c6-".$c6." as c6,c7-".$c7." as c7,c8-".$c8." as c8,c9-".$c9." as c9,c10-".$c10." as c10,c11-".$c11." as c11,c12-".$c12." as c12,c13-".$c13." as c13,c14-".$c14." as c14,users,project,node from data_original where users='".$father_url."' and project='".$child_url."' and node='".$node."';";
$resultzero_2 = mysqli_query($con,$sqlzero_2);
//echo $sqlzero_2;
$sql1 = "SELECT COUNT(*) FROM data_original WHERE users = '".$father_url."' AND project = '".$child_url."' and node='".$node."';";
$result = mysqli_query($con,$sql1); 
$rows = mysqli_fetch_row($result);
$count=$rows[0];
echo "<br />sun: ".$count.'个<br />';


$sql1 = "UPDATE node SET node_filename='".$file."',node_size='".$count."' ,newdata = '1' where node_user='".$father_url."' and node_id='".$node."' and node_project='".$child_url."';";
//echo $sql1;
$result = mysqli_query($con,$sql1); 
$t3 = microtime(true);
echo '<br>耗时'.round($t3-$t1,3).'秒<br>';
mysqli_close($con);


function addDetail($con,$rows,$users,$project,$node){
		if(empty($rows)){
			return false;
		}
		//数据量较大,采取批量插入
		$data = implode(',', $rows);
		$sql = "INSERT  INTO data_temp(times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node)
				 VALUES {$data}";
		//print_r($sql);
		$result = mysqli_query($con,$sql); 
		$rc = mysqli_affected_rows($con);
		echo "receive: " . $rc;
		$result = mysqli_query($con,'INSERT INTO data_original (times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node) SELECT times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node FROM data_temp WHERE NOT EXISTS (SELECT times,users,project,node FROM data_original WHERE data_original.times = data_temp.times AND data_original.users = data_temp.users AND data_original.project = data_temp.project AND data_original.node = data_temp.node)'); 
		$rc = mysqli_affected_rows($con);
		echo "<br>success updata: " . $rc;
		$sql ="DELETE FROM data_temp where users='".$users."' and project='".$project."' and node='".$node."';";
		$result = mysqli_query($con,$sql);
		
		return true;
}
?>