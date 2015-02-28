<?php
echo "<!-- COPYRIGHT COMIS 2014 TO ".date("Y");
$_SESSION['db_prefix'] = $db_prefix["db_prefix"]."_";
echo " -->\n";
function pref($name) {
	$oo=conf("oo");
//	OO OPTIONAL WILL EXPIRE SOON
	if($oo) {
		global $db;
	
		if(file_exists("../etc/db.conf"))
			$import=file("../etc/db.conf");
		else
			$import=file("etc/db.conf");
		$type=array_shift($import);
		$type=substr($type, 1);
		foreach($import as $conf_f) {
			$import_tmp=explode("=",$conf_f);$db_prefix[$import_tmp[0]]=str_replace(PHP_EOL,null,$import_tmp[1]);
		}
		$_SESSION['db_prefix'] = $db_prefix["db_prefix"]."_";$value=false;
		$result = $db->query("select * from `".$_SESSION['db_prefix']."preferences` where name = '".$name."'");
		while($row = $result->fetch_object()) {
			$value = $row->value;
			if($name=="language" && isset($_GET['l']))
				$value = $_GET['l'];
		}
		return $value;
	} else {
		if(file_exists("../etc/db.conf"))
			$import=file("../etc/db.conf");
		else
			$import=file("etc/db.conf");
		$type=array_shift($import);
		$type=substr($type, 1);
		foreach($import as $conf_f) {
			$import_tmp=explode("=",$conf_f);$db_prefix[$import_tmp[0]]=str_replace(PHP_EOL,null,$import_tmp[1]);
		}
		$_SESSION['db_prefix'] = $db_prefix["db_prefix"]."_";
		$result = mysql_query("select * from `".$_SESSION['db_prefix']."preferences` where name = '".$name."'");
		while($row = mysql_fetch_array($result)) {
			$value = $row[value];
			if($name=="language" && $_GET['l']!="")
				$value = $_GET['l'];
		}
		return $value;
	}
}
function show($name) {
	$oo=conf("oo");
//	OO OPTIONAL WILL EXPIRE SOON
	if($oo) {
		global $db;
		$result = $db->query("select * from `".$_SESSION['db_prefix']."preferences` where name = '".$name."'");
		while($row = $result->fetch_object()) {
			$value = $row->value;
		}
	} else {
		$result = mysql_query("select * from `".$_SESSION['db_prefix']."preferences` where name = '".$name."'");
		while($row = mysql_fetch_array($result)) {
			$value = $row[value];
		}
	}
	echo $value;
}
function addlog($msg) {
	$log = time()."#".$msg."\n";
	if(file_exists("log/useractions.log")) 
		file_put_contents("log/useractions.log",(file_get_contents("log/useractions.log").$log));
	elseif(file_exists("../log/useractions.log")) 
		file_put_contents("../log/useractions.log",(file_get_contents("../log/useractions.log").$log));
	elseif(is_dir("log"))
		file_put_contents("../log/useractions.log",$log);
	elseif(is_dir("../log"))
		file_put_contents("../log/useractions.log",$log);
}
function addlog2($msg) {
	$log = time()."#".$msg."\n#####\n";
	if(file_exists("log/system.log"))
		file_put_contents("log/system.log",(file_get_contents("log/system.log").$log));
	elseif(file_exists("../log/system.log"))
		file_put_contents("../log/system.log",(file_get_contents("../log/system.log").$log));
	elseif(is_dir("log"))
		file_put_contents("../log/system.log",$log);
	elseif(is_dir("../log"))
		file_put_contents("../log/system.log",$log);
}
function alert($msg) {
	echo "<script>alert(\"".$msg."\");</script>";
}
function show_editicons($id,$pageid=NULL) {
	global $text;
	if(is_admin())
	echo "<div style=float:right>
	<a class='changeopacity icon' onmousedown=fadeOut(); href=admin/?action=2&a=edit&id=".$id."&view_next=true&pageid=".$pageid."><img src=templates/main/images/edit.png alt='' style=height:27px;margin-top:-7px;margin-bottom:-5px;></a>
	<a class='changeopacity icon' onmousedown=fadeOut(); href='admin/?action=2&a=delete&id=".$id."&view_next=true&pageid=".$pageid."' onmouseup=\"alert('".$text["shure"]."');\"><img src=templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:0px;margin-bottom:-5px;></a>
	</div>";
}
function is_admin() {
	return(@$_COOKIE['username']=="admin" || @$_COOKIE['adminaccess']=="yes" || @$_COOKIE['adminaccess']=="admin_like")?1:0;
}
function is_sudo() {
	return(@$_COOKIE['username']=="admin")?1:0;
}
function ending($filename) {
	if(filetype($filename)=="image/jpeg")
		$ending=".jpeg";
	elseif(filetype($filename)=="image/png")
		$ending=".png";
	elseif(filetype($filename)=="image/gif")
		$ending=".gif";
	elseif(filetype($filename)=="audio/wav")
		$ending=".wav";
	elseif(filetype($filename)=="audio/mp3")
		$ending=".mp3";
	elseif(filetype($filename)=="video/mp4")
		$ending=".mp4";
	else {
		$ending=".jpg";
	}
	return $ending;
}
function conf($pref) {
	$conf="etc/main.conf";
	if(file_exists($conf)) $confp=$conf;
	elseif(file_exists("../".$conf)) $confp="../".$conf;
	elseif(file_exists("../../".$conf)) $confp="../../".$conf;
	$conf=import_comis($confp);
	$confpref=str_replace("\r","",str_replace("\n","",$conf[$pref]));
	if($confpref=="false"||$confpref=="FALSE"||$confpref=="no"||$confpref==""||$confpref=="0") $confpref=false;
	return $confpref;
}
function setconf($pref,$val) {
//	NOT WORKING PROPERLY
	$conf="etc/main.conf";
	if(file_exists($conf)) $confp=$conf;
	elseif(file_exists("../".$conf)) $confp="../".$conf;
	elseif(file_exists("../../".$conf)) $confp="../../".$conf;
	$data=file_get_contents($confp)."\n".$pref."=".$val;
	if(file_put_contents($confp,data))
	return true;
}
function smkdir($dir) {
	if(!is_dir($dir)) mkdir($dir);
}
function error($id) {
	if(conf("show_errors"))	echo "Error ".$id.". <a href=//domiscms.de/errors/".$id.">More Info</a>";
}
function is_user() {
	return(@$_COOKIE["username"]!="")?1:0;
}
function reload_page() {
	echo "<script>window.location.href=window.location.href;</script>";
}
?>