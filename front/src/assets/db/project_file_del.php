<?php 
$father_url= $_GET['father_url'];
$child_url= $_GET['child_url'];
$file= $_GET['file'];
$dir = iconv("UTF-8", "GBK", "upload/".$father_url."/". $child_url."/".$file);
//echo $dir;
if (!unlink($dir))
{
	echo '{"success": "0"}'; 
}
else
{
	echo '{"success": "1"}'; 
}

?>