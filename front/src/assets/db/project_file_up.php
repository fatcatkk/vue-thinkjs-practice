<?php
// 允许上传的图片后缀
header('Access-Control-Allow-Methods:post');
header("Content-Type:text/html;charset=UTF-8");
$father_url = $_REQUEST['father_url'];
$child_url = $_REQUEST['child_url'];
//echo "father_url:".$father_url;
//echo "child_url:".$child_url;
$allowedExts = array("dat","txt","csv");
$temp = explode(".", $_FILES["file"]["name"]);
//echo $_FILES["file"]["size"];
$extension = end($temp);     // 获取文件后缀名
if (($_FILES["file"]["size"] < 2048000)   // 小于 200 kb
&& in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "错误：: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        //echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
        //echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
        //echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        //echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
        
        // 判断当期目录下的 upload 目录是否存在该文件
        // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
		
		$dir = iconv("UTF-8", "GBK", "upload/".$father_url."/". $child_url."/");
		$filenames= iconv("UTF-8", "GBK", $_FILES["file"]["name"]);
		$upurl=$dir.$filenames;
		//echo $dir;
		//echo $filenames;
		//echo $upurl;
		if (!file_exists($dir)){
            mkdir ($dir,0777,true);
            //echo '创建文件夹bookcover成功';
        } 
        if (file_exists($upurl))
        {
            //echo $_FILES["file"]["name"] . " 文件已经存在。 ";
			echo '{"success":"2","name":"'. $filenames.'","url":"'.$upurl.'"}';
        }
        else
        {
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
            move_uploaded_file($_FILES["file"]["tmp_name"], $dir.$filenames);
			echo  '{"success":"1","name":"'. $filenames.'","url":"'.$upurl.'"}';
        }
    }
}
else
{
    echo  '{"success":"0","name":"'. $filenames.'","url":"'.$upurl.'"}';
}

?>