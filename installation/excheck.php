<?php
if(!file_exists("index.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
if(file_exists("../lib/global_functions.php")) {
	require_once("../lib/global_functions.php");
	$db_conf=import_comis("../etc/db.conf");
}
$db_username=$db_conf["db_username"];
$db_password=$db_conf["db_password"];
$db_host=$db_conf["db_host"];
$db_name=$db_conf["db_name"];
$db_prefix=$db_conf["db_prefix"];echo "<center>";
if(mysql_connect($db_host,$db_username,$db_password) && mysql_select_db($db_name))
	echo "<font color=green>Perfect, you did it! Click Continue</font>";	
else
	echo "<font color=red>MySQL Cannot Connect yet.</font>";	
?>
<meta http-equiv="refresh" content="3,excheck.php">
