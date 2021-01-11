<?php
header("content-type:text/html;charset=UTF-8");
ini_set('date.timezone','Asia/Shanghai'); // 'Asia/Shanghai' 为上海时区 
error_reporting( E_ALL&~E_NOTICE&~E_WARNING);
/*
ob_end_clean();#清除之前的缓冲内容，这是必需的，如果之前的缓存不为空的话，里面可能有http头或者其它内容，导致后面的内容不能及时的输出
header("Connection: close");//告诉浏览器，连接关闭了，这样浏览器就不用等待服务器的响应
header("HTTP/1.1 200 OK"); //可以发送200状态码，以这些请求是成功的，要不然可能浏览器会重试，特别是有代理的情况下
ob_start();#开始当前代码缓冲
echo "后台已经开始执行，请干点别的事情";
$size=ob_get_length();
header("Content-Length: $size");
ob_end_flush();#输出当前缓冲
flush();#输出PHP缓冲
ignore_user_abort(); // 后台运行*/
set_time_limit(0); // 取消脚本运行时间的超时上限
ini_set('memory_limit', '-1'); //内存无限

 if ($fp = fopen ( 'lock.txt', 'a' )) {
   $startTime = microtime ();
   do {
      $canWrite = flock ( $fp, LOCK_EX );
      if (! $canWrite)
      usleep ( round ( rand ( 0, 100 ) * 1000 ) );
   } while ( (! $canWrite) && ((microtime () - $startTime) < 1000) );
   if ($canWrite) {

			   echo 'start.';
			   while(1){
				   $filename="Autorun_log_".date("Y-m-d" ).".log";
					$fp1 = fopen($filename,'a+');
//////////////////////////////////////////////				include 'project_AUTO_updata.php'; 
						$father_url= 'admin';//$_GET['father_url'];
						$child_url= '25';//$_GET['child_url'];
						$project= $child_url;
						include 'conn.php'; 
						$sql = "SELECT * from project where id='".$child_url."' limit 1";
						$result = mysqli_query($con,$sql); 
						$rowproject = mysqli_fetch_array($result);
						$nodenum =$rowproject[node];
						$autorun =$rowproject[autorun];
						//$nodenum=$rowproject[node];
						//mysqli_free_result($result);
						
						fwrite($fp1,date("Y-m-d H:i:s   " ) ."node number:". $nodenum."\r\n");
						if($autorun=='1'){
							/////////////////////////////////////////文件上传////////////////////////////////////////////////////////////////
								$t1 = microtime(true);
								$sql = "SELECT * from node where node_user='".$father_url ."' and node_project = '".$child_url."' order by CAST(node_id AS UNSIGNED) limit ".$nodenum;
								
								//$k=0;
								//mysqli_free_result($result0);
								$result0 = mysqli_query($con,$sql); 
								$arr = array(); 
								$rowk = array(); 

								while($rowk = mysqli_fetch_array($result0)) { 
								  
								  $count=count($rowk);//不能在循环语句中，由于每次删除 row数组长度都减小 
								  for($j=0;$j<$count;$j++){ 
									unset($rowk[$j]);//删除冗余数据 
								  }
									$node= $rowk['node_id'];
									$file= $rowk['node_filename'];

									fwrite($fp1,$file."   ");
									$dir = iconv("UTF-8", "GBK", "upload/".$father_url."/". $child_url."/".$file);
								////////////////////////////////////////////////////////////////////////////////////////////////////////////
										
									$excelData = array();
									$content = trim(file_get_contents($dir));
									//print_r($content);
									//echo $content;//将文件一次性全部读出来
									//echo "<br>";
									$excelData = explode("\r",$content);
									
									$sum= count($excelData);
									//echo "sum:".$sum;
									$chunkData = array_chunk($excelData, 50000); // 将这个10W+ 的数组分割成5000一个的小数组。这样就一次批量插入5000条数据。mysql 是支持的。
									
									$count = count($chunkData);
									$rcc=0;$rc=0;
									//echo $count;
									//fwrite($fp1,"count:".$count."   ");
									for ($i = 0; $i < $count; $i++) {
										$insertRows = array();
										foreach($chunkData[$i] as $value){
											$string = trim(strip_tags($value));//转码
											$v = explode(' ', trim($string));
											//print_r($v);
											$row = array();
											$row['1']    = empty($v[0].' '.$v[1]) ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',strtotime($v[0].' '.$v[1]));
											$row['2'] 	=  $v[2];
											if(empty($row['2']))
											{
												break;
											}
											$row['3']   = $v[3];
											$row['4']   = $v[4];
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
										
										if(empty($insertRows)){
											return false;
										}
										//数据量较大,采取批量插入
										$data = implode(',', $insertRows);
										//echo $data;
										$sql = "INSERT  INTO data_temp(times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node)
												 VALUES {$data}";

										$result1 = mysqli_query($con,$sql); 
										$rcc += mysqli_affected_rows($con);
										fwrite($fp1, "receive:".$rcc."   ");
										
										$result1 = mysqli_query($con,'INSERT INTO data_original (times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node) SELECT times,c1,c2,c3,c4,c5,c6,c7,c8,c9,c10,c11,c12,c13,c14,users,project,node FROM data_temp WHERE NOT EXISTS (SELECT times,users,project,node FROM data_original WHERE data_original.times = data_temp.times AND data_original.users = data_temp.users AND data_original.project = data_temp.project AND data_original.node = data_temp.node)'); 
										$rc += mysqli_affected_rows($con);
										fwrite($fp1,"success:".$rc."   ");

										$sql ="DELETE FROM data_temp where users='".$father_url."' and project='".$child_url."' and node='".$node."';";
										$result2 = mysqli_query($con,$sql);
									}//for count
									if($rc>0)
										{
											//////////////////以上是文件读取插入data_original表内/////////////////////////////有问题，暂时先不管
											$sqlzero_0 ="DELETE FROM data where users='".$users."' and project='".$project."' and node='".$node."';";//清空data表
											$resultzero_0 = mysqli_query($con,$sqlzero_0);
											$sqlzero_1 ="select *  from data_original where users='".$father_url."' and project='".$child_url."' and node='".$node."' order by times asc limit 1;";//找到曲线的每一条的第一个值
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
											//////////////////以上是文件经过归零之后存到data表内/////////////////////////////
											$sql1 = "UPDATE node SET node_filename='".$file."',node_size='".$rc."' ,newdata = '1' where node_user='".$father_url."' and node_id='".$node."' and node_project='".$child_url."';";
											//echo $sql1;
											$result4 = mysqli_query($con,$sql1);
										///////////////////////////下面开始计算////////////////////////////////	
												
									}//if new data
									if(($rc>0)||($rowk[newdata]>0))
									{//////////////计算step1///////////////////////////////
											$NH=$rowk['NH'];
											$NZ=$rowk['NZ'];
											$sql="update data set TH=(";
											$node_1_9 = explode(',',$rowk['node_1_9']); 
											$node_10_12 = explode(',',$rowk['node_10_12']); 
											for($index=0;$index<count($node_1_9);$index++){ 
											$sql=$sql."c"; 
											$sql=$sql.$node_1_9[$index];
											$sql=$sql."+"; 
											}
											$sql=$sql."0)/".$NH." , TZ= ("; 
											for($index=0;$index<count($node_10_12);$index++){ 
											$sql=$sql."c"; 
											$sql=$sql.$node_10_12[$index];
											$sql=$sql."+"; 
											}
											$sql=$sql."0)/".$NZ." where project='".$child_url."' and node='".$node."';"; 
											//echo $sql;
											$step1 = mysqli_query($con,$sql); 
										//////////////计算step2/////////////////////////////
											$r=$rowk['r'];
											$a=$rowk['a'];
											$E=$rowk['E'];
											$V=$rowk['V'];
											$U=$rowk['U'];
											$E1=$rowk['E1'];
											$V1=$rowk['V1'];
											$NH=$rowk['NH'];
											$NZ=$rowk['NZ'];
											$RR=$rowk['RR'];
											$M=$rowk['M'];
											$K0=$rowk['K0'];
											$sql="update data set circ=((".$E."*(TH+3*".$V."*TZ))/(3*(".$M."-".$V."*".$V."))),axis=TZ*".$E."+(".$E."*".$U."*(TH+3*".$V."*TZ))/(3*(".$M."-".$V."*".$V.")) where project='".$child_url."' and node='".$node."';"; 
											//echo $sql;
											$step2 = mysqli_query($con,$sql); 
												//echo "<br>";
										//////////////////newdata写0，避免重复执行//////////////////////////////
										$sql = "update node set newdata='0' where node_project='".$child_url."' ;";
										$newdata = mysqli_query($con,$sql); 
										
									}
									$sql1 = "SELECT COUNT(*) FROM data WHERE users = '".$father_url."' AND project = '".$child_url."' and node='".$node."';";
									$result3 = mysqli_query($con,$sql1); 
									$roww = mysqli_fetch_row($result3);
									$countc=$roww[0];
									fwrite($fp1,"sum:".$countc."\r\n");
									 
									
								} //while node
							/////////////////////////////////////////数据计算step1////////////////////////////////////////////////////////////////	
							$t2 = microtime(true);
							//echo '耗时'.round($t2-$t1,3).'秒\r\n';
						}
						
				
							mysqli_free_result($result0);
							mysqli_free_result($result1);
							mysqli_free_result($result2);
							mysqli_free_result($result3);
							mysqli_free_result($resultzero_1);
							mysqli_free_result($resultzero_2);
							mysqli_free_result($step1);
							mysqli_free_result($step2);
							mysqli_free_result($newdata);
							
							mysqli_close($con);
////////////////////////////////////////////////////////////////////////////
				//fwrite($fp1,date("Y-m-d H:i:s   ") . " 成功了！\r\n");
				fclose($fp1);
				sleep(20);
			   }
			   
			   
	   echo 'end.';
   }
   fclose ( $fp );
   die('task kill');
   
   
   
   

 }






?>