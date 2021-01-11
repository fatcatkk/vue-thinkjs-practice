<?php 
error_reporting( E_ALL&~E_NOTICE );
header("content-type:text/html;charset=UTF-8");
$father_url= 'admin';//$_GET['father_url'];
$child_url= '25';//$_GET['child_url'];
//$node= $_GET['node'];
//$file= $_GET['file'];

$t1 = microtime(true);

include 'conn.php'; 

$sql = "SELECT node from project  where token='".$father_url ."' and id = '".$child_url."'limit 1";
$result0 = mysqli_query($con,$sql); 
$row = mysqli_fetch_array($result0);
$sum =$row[0];
echo  $sum;
echo "<br>";

$sql = "SELECT node_id,node_filename from node where node_user='".$father_url ."' and node_project = '".$child_url."' order by CAST(node_id AS UNSIGNED) limit ".$sum;
echo $sql;
echo "<br>";
$k=0;
$result1 = mysqli_query($con,$sql); 
$arr = array(); 
$row = array(); 

while($row[$k] = mysqli_fetch_array($result1)) { 
  $count=count($row[$k]);//不能在循环语句中，由于每次删除 row数组长度都减小 
  for($j=0;$j<$count;$j++){ 
    unset($row[$k][$j]);//删除冗余数据 
  }
	$node= $row[$k][node_id];
	$file= $row[$k][node_filename];
  //print_r($row);
	echo $node;
	echo "<br>";
	echo $file;
	echo "<br>";
	$dir = iconv("UTF-8", "GBK", "upload/".$father_url."/". $child_url."/".$file);
	echo $dir;//将文件一次性全部读出来
	echo "<br>";
////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	$excelData = array();
	$content = trim(file_get_contents($dir));
	//echo $content;//将文件一次性全部读出来
	echo "<br>";
	$excelData = explode("\r",$content);
	$sum= count($excelData);
	echo "sum:".$sum;
	$chunkData = array_chunk($excelData, 50000); // 将这个10W+ 的数组分割成5000一个的小数组。这样就一次批量插入5000条数据。mysql 是支持的。
	//print_r($chunkData);
	$count = count($chunkData);
	echo "count:".$count."<br>";
	for ($i = 0; $i < $count; $i++) {
		$insertRows = array();
		foreach($chunkData[$i] as $value){
			$string = mb_convert_encoding(trim(strip_tags($value)), 'utf-8', 'GBK');//转码
			$v = explode(' ', trim($string));
			//print_r($v);
			$row[$k] = array();
			$row[$k]['1']    = empty($v[0].' '.$v[1]) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',strtotime($v[0].' '.$v[1]));
			$row[$k]['2'] = $v[2];
			$row[$k]['3']  = $v[3];
			$row[$k]['4'] = $v[4];
			$row[$k]['5']   = $v[5];
			$row[$k]['6']    = $v[6];
			$row[$k]['7']	 = $v[7];
			$row[$k]['8']    = $v[8];
			$row[$k]['9']    = $v[9];
			$row[$k]['10']    = $v[10];
			$row[$k]['11']    = $v[11];
			$row[$k]['12']    = $v[12];
			$row[$k]['13']    = $v[13];
			$row[$k]['14']    = $v[14];
			$row[$k]['15']    = $v[15];
			$row[$k]['16']    = $father_url;
			$row[$k]['17']    = $child_url;
			$row[$k]['18']    = $node;
			$sqlString       = '('."'".implode( "','", $row[$k] ) . "'".')'; //批量
			$insertRows[]    = $sqlString;
			//echo " <br>//////////////////44/////////////////// <br>";
		}
		//print_r($insertRows);
		
		addDetail($con,$insertRows,$father_url,$child_url,$node); //批量将sql插入数据库。
		
	}
	$sql1 = "SELECT COUNT(*) FROM data WHERE users = '".$father_url."' AND project = '".$child_url."' and node='".$node."';";
	$result2 = mysqli_query($con,$sql1); 
	$rows = mysqli_fetch_row($result2);
	$count=$rows[0];
	echo "<br />sun: ".$count.'个<br />';

	$k++;
	$sql1 = "UPDATE node SET node_filename='".$file."',node_size='".$count."' where node_user='".$father_url."' and node_id='".$node."' and node_project='".$child_url."';";
	echo $sql1;
	$result3 = mysqli_query($con,$sql1); 
	
} 


$t2 = microtime(true);
echo '耗时'.round($t2-$t1,3).'秒<br>';
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
		$result = mysqli_query($con,'INSERT INTO data (times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node) SELECT times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node FROM data_temp WHERE NOT EXISTS (SELECT times,users,project,node FROM data WHERE data.times = data_temp.times AND data.users = data_temp.users AND data.project = data_temp.project AND data.node = data_temp.node)'); 
		$rc = mysqli_affected_rows($con);
		echo "<br>success updata: " . $rc;
		$sql ="DELETE FROM data_temp where users='".$users."' and project='".$project."' and node='".$node."';";
		$result = mysqli_query($con,$sql);
		
		return true;
}
?>