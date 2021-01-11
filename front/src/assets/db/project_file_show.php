<?php 
$father_url= $_GET['father_url'];
$child_url= $_GET['child_url'];
$file= $_GET['file'];
$dir = iconv("UTF-8", "GBK", "upload/".$father_url."/". $child_url."/".$file);
//echo $dir;
 
$excelData = array();
$content = trim(file_get_contents($dir));
$excelData = explode("\r",$content);

$chunkData = array_chunk($excelData, 1); // 将这个10W+ 的数组分割成5000一个的小数组。这样就一次批量插入5000条数据。mysql 是支持的。
//print_r($chunkData);
$count = count($chunkData);
$arr = array(); 
for ($i = 0; $i < $count; $i++) {
	$insertRows = array();
	foreach($chunkData[$i] as $value){
		$string = mb_convert_encoding(trim(strip_tags($value)), 'utf-8', 'gbk');//转码
		$row['datafile'] = $string;
		array_push($arr,$row); 
	}
}
echo json_encode($arr,JSON_UNESCAPED_UNICODE); 
?>