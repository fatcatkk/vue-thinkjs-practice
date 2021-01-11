<?php
 
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");

$father_url = $_REQUEST['father_url'];
$child_url = $_REQUEST['child_url'];
$dir1 = iconv("UTF-8", "GBK", "upload/".$father_url."/". $child_url."/");
$dir = "upload/".$father_url."/". $child_url."/";
$upurl=$dir;
class Commonfile{
 
var $text = "";
var $leaf = true;
public function __construct($text, $leaf){
 
$this->text = $text;
$this->leaf = $leaf;
}
}

getFile($upurl,$father_url,$child_url);

function getFile($dir,$father_url,$child_url) {
    $fileArray[]=NULL;
	if (!file_exists($dir)){
            mkdir ($dir,0777,true);
            //echo '创建文件夹bookcover成功';
    } 
    if (false != ($handle = opendir ( $dir ))) {
        $i=0;
        while ( false !== ($file = readdir ( $handle )) ) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != ".."&&strpos($file,".")) {
                $fileArray[$i]=array(
									 "value"=>$i+1,
									 "label"=>iconv("GBK", "UTF-8", $file),
									 );
                if($i==1000){
                    break;
                }
                $i++;
            }
        }
        //关闭句柄
        closedir ( $handle );
    }

	$a = array(
     "name"=>"data1",
    "id"=>$_REQUEST['child_url'],
    "channel"=>"3",
    "update"=>"2019-1-30 11:30:30",
 );

/////////////////////////////////////////////////////////////////////////////////

include 'conn.php';
$sql = "SELECT node from project  where token='".$father_url ."' and id = '".$child_url."'limit 1";

$result = mysqli_query($con,$sql); 
$row = mysqli_fetch_array($result);
$sum =$row[0];
//echo $sum;

$sql = "SELECT Id ,node_name,node_id,node_size,node_time,node_filename from node where node_user='".$father_url ."' and node_project = '".$child_url."' order by CAST(node_id AS UNSIGNED) asc limit ".$sum;
//echo $sql;
$result = mysqli_query($con,$sql); 
 
$arr = array(); 
while($row = mysqli_fetch_array($result)) { 
  $count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
  for($i=0;$i<$count;$i++){ 
    unset($row[$i]);//删除冗余数据 
  } 
  $row['datafile'] = $fileArray;

  array_push($arr,$row); 
 
} 

echo json_encode($arr,JSON_UNESCAPED_UNICODE); 
mysqli_close($con); 
/////////////////////////////////////////////////////////////////////////////////

return $fileArray;
}
 




?>