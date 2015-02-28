<?php
//Includes global functions connects to database
//Bearbeitete Datei
if(file_exists("lib/import_function.php")) {
	require_once("lib/import_function.php");
	$db_conf=import_comis("etc/db.conf");
}
elseif(file_exists("../lib/import_function.php")) {
	require_once("../lib/import_function.php");
	$db_conf=import_comis("../etc/db.conf");
	$pre="../";
}
$preventsqlinj=true; // By setting this value to false, your Website might becomes vulnerable to SQL Injection !!!
if($preventsqlinj) {
	function prevsqlinj (&$value, $key) {
		$value = trim(htmlspecialchars($value, ENT_QUOTES));
	}
	array_walk ($_GET, 'prevsqlinj');
	array_walk ($_POST, 'prevsqlinj');
}
$db_username=$db_conf["db_username"];
$db_password=$db_conf["db_password"];
$db_host=$db_conf["db_host"];
$db_name=$db_conf["db_name"];
$db_prefix=$db_conf["db_prefix"]."_";
if(@mysql_connect($db_host,$db_username,$db_password)) {
	@mysql_select_db($db_name);
}
else {
	if(file_exists($pre."installation/index.php"))
		die("<meta http-equiv=refresh content='0,".$pre."installation/index.php'><script>window.location.href='".$pre."installation/index.php';</script>");
	elseif(rename("installation/reinstall.php","installation/index.php")) {
		die("<body style=background:#457c9a><br><br><center><img src=".$pre."images/logo_1000.png width=400 style=margin:100px><h1 style=color:white;font-family:arial>COULD NOT CONNECT TO DATABASE!<br><br> PLEASE <a href=".$pre."installation/index.php style=color:white;font-weight:italic;text-decoration:none;color:orange>CLICK HERE</a> TO REINSTALL COMIS<div style=display:none; id=errors>");error(201502241345);
	}
}
if(isset($db_conf["timezone"]))date_default_timezone_set($db_conf["timezone"]);
$db=mysqli_connect($db_host,$db_username,$db_password,$db_name);
if ($db->connect_error)
	trigger_error('Database connection failed: '  . $db->connect_error, E_USER_ERROR);
if(file_exists("lib/global_functions.php"))
	require_once("lib/global_functions.php");
elseif(file_exists("../lib/global_functions.php"))
	require_once("../lib/global_functions.php");
?>