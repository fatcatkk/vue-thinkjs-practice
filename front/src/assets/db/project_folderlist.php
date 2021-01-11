<?php
header("content-type:text/html;charset=UTF-8");
date_default_timezone_set("Asia/Shanghai");


$father_url = $_REQUEST['father_url'];
$child_url = $_REQUEST['child_url'];

$dir1 = iconv("UTF-8", "GBK", "upload/".$father_url."/". $child_url."/");
$dir = "upload/".$father_url."/". $child_url."/";

$upurl=$dir;
//echo '[{"name": "data1","id": "1000002","channel": "3","update": "2019-1-30 11:30:30"}, {"name": "data2","id": "25","channel": "4","update": "2019-1-30 11:30:30"}, {"name": "data3","id": "30","channel":"5","update": "2019-1-30 11:30:30"}, {"name": "data4","id": "26","channel": "6","update": "2019-1-30 11:30:30"}]';

class Commonfile{
 
var $text = "";
var $leaf = true;
public function __construct($text, $leaf){
 
$this->text = $text;
$this->leaf = $leaf;
}
}

getFile($upurl);

function getFile($dir) {
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
                //$fileArray[$i]=array("name"=>$dir.iconv("GBK", "UTF-8", $file),"bar" => filesize($dir.iconv("GBK", "UTF-8", $file)));
                $fileArray[$i]=array("name"=>iconv("GBK", "UTF-8", $file),
									 "size" => round(filesize($dir.iconv("UTF-8", "GBK", iconv("GBK", "UTF-8", $file)))/1024,0)."K",
									 "fileatime"=>date("Y-m-d G:i:s",filectime($dir.iconv("UTF-8", "GBK", iconv("GBK", "UTF-8", $file)))),
									 );
				//array_push($fileArray, new Commonfile($file, '33m'));
				//array_push($fileArray,"搜狗浏览器","火狐浏览器");
                if($i==1000){
                    break;
                }
                $i++;
            }
        }
        //关闭句柄
        closedir ( $handle );
    }
	//echo "<pre>";print_r($fileArray);echo "<pre>";
	echo json_encode($fileArray,JSON_UNESCAPED_UNICODE);
    return $fileArray;
}
?>