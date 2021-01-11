<?php
//header("content-type:text/html;charset=utf-8");
$token= $_GET['token'];
$project_id= $_GET['project_id'];
$project_names= $_GET['project_name'];
$project_nodes= $_GET['project_node'];
$ch_num=intval($_GET['project_node']);
include 'conn.php';
//$sql = "INSERT INTO project (name,node,token)  VALUES ('".$project_names."','".$project_nodes."','".$token."');";
$sql = "UPDATE project SET name='".$project_names."',node='".$project_nodes."' where token='".$token."' and id='".$project_id."';";
//echo $sql;
$result = mysqli_query($con,$sql); 
//echo $result;
if($result)//插入成功后返回true，失败返回false
{
	$node_ID=$project_id;
	for ($i=1; $i<=$ch_num; $i++)
	{
		$sql1="INSERT INTO node(node_name,node_id,node_filename,node_size,node_time,node_project,node_user)SELECT '".$i."节点','".$i."','-','0','-','". $node_ID."','".$token."' FROM dual WHERE not exists (select * from node where node_id = '".$i."'and node_project='".$node_ID."' and node_user='".$token."');";
		$result = mysqli_query($con,$sql1); 
	}
 echo '{"success": "1"," id": "'. $node_ID.'"}'; 
}
else 
 echo '{"success": "0"}'; 

//echo json_encode($arr,JSON_UNESCAPED_UNICODE); 
mysqli_close($con); 

//echo '[{"name": "data1","id": "1000002","channel": "3","update": "2019-1-30 11:30:30"}, {"name": "data2","id": "25","channel": "4","update": "2019-1-30 11:30:30"}, {"name": "data3","id": "30","channel":"5","update": "2019-1-30 11:30:30"}, {"name": "data4","id": "26","channel": "6","update": "2019-1-30 11:30:30"}]'
?>