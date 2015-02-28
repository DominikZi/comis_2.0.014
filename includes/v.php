<?php 
//THIS DOCUMENT WILL BE REMOVED IN A LATER VERSION OF COMIS
if(file_exists("lib/global_functions.php")) {
	include_once("lib/global_functions.php");
	$about_comis=import_comis("etc/about.conf");
}
elseif(file_exists("../lib/global_functions.php")) {
	include_once("../lib/global_functions.php");
	$about_comis=import_comis("../etc/about.conf");
}
$v=$about_comis["version"];
$v = str_replace(PHP_EOL,null,$v)
?>