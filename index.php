<?php
/**
 * @file	index.php 
 * @author	Dominik Ziegenhagel <domi@domisseite.de>
 * @thinking-he-is-author	Udo Ziegenhagel <udo.ziegenhagel@gmx.de>
 * @version	2.0 COMIS ICECUBE	from 2014/11/06 -dz-
 *
 * @date		2014-11-06
 *
 * @section DESCRIPTION
 *
 *	index.php :
 *	Hier wird entweder gestartet\n
 * 	- die Installation, wenn das subdir installation existiert
 * 	- ein Update wenn das subdir update existiert
 *	- das programm comis, wenn etc/db.php existiert
 *
 */

$COMIS = 42;
$COMIS_CONFIG		= "etc/comis.conf";
$COMIS_DIR		= __DIR__;

session_start();

include("lib/index_functions.php");
$index=new index();

$allowed_do=array("logout","login","unsubscribe","back_to_admin");
$allowed_do[]="autologin";
if(in_array(@$_GET["do"],$allowed_do)) {
	$index->do_what($_GET["do"]);
	return;
}
if(file_exists("etc/db.conf"))
  include('db.php');
else {
	$index->no_db();
	return;
}
if($_SESSION["rename"]===true)
	if(is_file("installation/index.php"))rename("installation/index.php","installation/reinstall.php");$_SESSION["rename"]=false;
if(file_exists("installation/index.php")) {
	$index->ask_4_install();
	$continue=true;
}
elseif(isset($_GET["ABORTINSTALLATION"])) {
	unlink("auto_install/index.php");	
	$continue=true;
}
elseif(file_exists("auto_install/index.php")) {
	$index->auto_install();
}
else
	$continue=true;
if($continue) {
	include_once('lib/global_functions.php');
	function setmycookie($name,$value) {
		echo "<iframe style=display:none src=lib/setcookie.php?name=".$name."&value=".$value."></iframe>";
	}
	if(file_exists('language/en.php'))include('language/en.php');
	if(isset($_GET["l"])) {
		if(file_exists('language/'.$_GET["l"].'.php')) {
			$_SESSION['l']=$_GET["l"];
			include('language/'.$_GET["l"].'.php');
		}
	}
	elseif(isset($_SESSION['l'])) {
		if(file_exists('language/'.$_SESSION['l'].'.php'))
			include('language/'.$_SESSION['l'].'.php');
	}
	else {
		if(file_exists('language/'.pref("language").'.php'))
			include('language/'.pref("language").'.php');
	}
	$index->desktop_or_mobile_sess();
	$index->ask_desktop_or_mobile_sess();
	
	// including main documents
	if(@$_SESSION['mobile']===true)
		include('includes/mobile.php');
	else
		include('includes/mainpage.php');
}
$db->close();
?>