<?php
include("../includes/v.php");
$data=$data."#backup#".$v;
$tables=array("articles","pages","user","groups","preferences","comments","forum_answers","forum_questions","shop_categories","shop_items","shop_useritems");
foreach($tables as $table) {
	$data=$data."#####?".$table."?#####";
	$result = mysql_query("select * from ".$db_prefix.$table." order by id desc");
	while($row = mysql_fetch_array($result)) {
		for($i=0;$i<count($row);$i++) {
			$data=$data.$row[$i]."#####";
		}
		$data=$data."+++++";
	}
}
file_put_contents("../backup/".substr(time(),0,strlen(time())-2)."00".".bckp",$data);
file_put_contents("../log/backup_timestamps.log",substr(time(),0,strlen(time())-2)."00"."\n".@file_get_contents("../log/backup_timestamps.log"));
?>