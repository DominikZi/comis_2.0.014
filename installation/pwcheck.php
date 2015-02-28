<?php
if(!file_exists("index.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
if(mysql_connect($db_host,$db_username,$db_password) && mysql_select_db($db_name))
	echo "<font color=green>Perfect, you did it! Click Continue</font>";	
else
	echo "<font color=red>MySQL Cannot Connect yet.</font>";	
?>