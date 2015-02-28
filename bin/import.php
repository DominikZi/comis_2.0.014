<?php
session_start();
include("../lib/global_functions.php");
if(!is_admin() && $_POST["key"]!=$_SESSION["key"]) return;
if(!is_dir("../files/tmp")) mkdir("../files/tmp");
$zip = new ZipArchive;
$uniqid="comis_export_".time().uniqid();
move_uploaded_file($_FILES['file']['tmp_name'], "../files/tmp/".$uniqid.'.zip');
if ($zip->open("../files/tmp/".$uniqid.'.zip') === TRUE) {
	$zip->extractTo('../files/tmp/'.$uniqid);
	$zip->close();
	if($_POST["options"]!="true") {
		$_POST["import_mainpage"]="yes";
		$_POST["import_db"]="yes";
		$_POST["import_aboutconf"]="yes";
		$_POST["import_mainconf"]="yes";
		$_POST["import_instconf"]="yes";
	}
	if(file_exists('../files/tmp/'.$uniqid."/mainpage.php") && $_POST["import_mainpage"] == "yes") {
		unlink("../includes/mainpage.php");
		rename('../files/tmp/'.$uniqid."/mainpage.php","../includes/mainpage.php");
	}
	if(file_exists('../files/tmp/'.$uniqid."/main.conf") && $_POST["import_mainconf"] == "yes") {
		unlink("../etc/main.conf");
		rename('../files/tmp/'.$uniqid."/main.conf","../etc/main.conf");
	}
	if(file_exists('../files/tmp/'.$uniqid."/inst.conf") && $_POST["import_instconf"] == "yes") {
		unlink("../etc/inst.conf");
		rename('../files/tmp/'.$uniqid."/inst.conf","../etc/inst.conf");
	}
	if(file_exists('../files/tmp/'.$uniqid."/db.conf") && ($_GET["import_db"]=="true" || $_POST["import_db"] == "yes")) {
		unlink("../etc/db.conf");
		rename('../files/tmp/'.$uniqid."/db.conf","../etc/db.conf");
//		rename("../installation/index.php","../installation/reinstall.php");
	}
	if(file_exists("../etc/about.conf") && $_POST["import_aboutconf"] == "yes") {
		unlink("../etc/about.conf");
		rename('../files/tmp/'.$uniqid."/about.conf","../etc/about.conf");
	} 
	echo '<script type="text/javascript">window.location.href="timetravel.php?fallback=backup&show=import&file=tmp/'.$uniqid.'&key='.$_SESSION["key"].'";</script>';
}
else echo "IMPORT FAILED";
unlink("../files/tmp/comis_export_".$uniqid.'.zip');
?>