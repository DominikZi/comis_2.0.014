<?php
if(!$_COOKIE['username']=="admin" && !$_COOKIE['adminaccess'] == "yes") {
	echo "<br><br><h2 style=text-align:center;>Bitte erst <a href=../?inc=login>einloggen</a>.</h2>";return;
}
if(isset($_GET["export"])) {
	$_GET["name"]='tmp';
}
include_once("../lib/import_function.php");
$about_comis=import_comis("../etc/about.conf");
$v=$about_comis["version"];
$v=str_replace("\n","",$v);
include("../db.php");
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
file_put_contents("../backup/".$_GET["name"].".bckp",$data);
file_put_contents("../log/backup_names.log",$_GET["name"]."\n".file_get_contents("../log/backup_names.log"));
if(isset($_GET["export"])) {
	echo "<script>
		window.location.href='../bin/export.php';
	</script><a href=../bin/export.php>".$text["click"]."</a>";
}
?>
<meta http-equiv=refresh content='0,../admin/?action=11&show=my_backups'>
<script type="text/javascript">
	window.location.href='../admin/?action=11&show=my_backups';
</script>