<?php
session_start();
if(!file_exists("index.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
if(isset($_SESSION["l"]) && file_exists("../language/".$_SESSION["l"].".php"))
	include("../language/".$_SESSION["l"].".php");
else
	include("../language/en.php");
if($_GET["db_password"]!="") {
	if(mysql_connect($_GET["db_host"],$_GET["db_username"],$_GET["db_password"]) && mysql_select_db($_GET["db_name"]))
	echo '<button type="submit" style="opacity:1;" class="btn btn-success">'.$text['8'].'</button>';
	else
		echo '<button type="submit" style="opacity:1;" class="btn btn-default">'.$text['8'].'</button>';
}
else
	echo '<button type="submit" style="opacity:1;" class="btn btn-default">'.$text['8'].'</button>';
?>
