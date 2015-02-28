<body style="background:#eee !important">
<link href="../templates/main/bootstrap.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../templates/main/jquery.min.js"></script>
<script type="text/javascript">
$("body").fadeOut(0);
$("body").fadeIn(500);
</script>
<?php
include('../db.php');
include("../language/".pref('language').".php");
echo '<link href="../templates/'.pref('admin_template').'/style.css" rel="stylesheet" type="text/css">';
echo '<link href="../templates/main/style.css" rel="stylesheet" type="text/css">';
?>
<div style=margin:14px;>
<?php
$logs = @array_reverse(file("../log/useractions.log"));
if(isset($logs))foreach($logs as $log) {
	echo "<div class=comment><b>".date("H:i:s d.m.Y",substr($log,0,10))."</b><br>".substr($log,11);
	if(file_exists("../log/auto_backup/".substr(substr($log,0,10),0,strlen(substr($log,0,10))-2)."00.bckp")) {
		echo  "<br><a href=../bin/timetravel.php?timestamp=".substr(substr($log,0,10),0,strlen(substr($log,0,10))-2)."00&fallback=all_logs>".$text["undo"]."</a>";
	}
	echo "</div>";
	$logs = true;	
}
