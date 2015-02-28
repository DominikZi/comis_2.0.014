<?php
session_start();
include("../db.php");
if(!is_admin()) $return=true;
if($_GET["key"]==$_SESSION["key"] && $_GET["key"]!="") {
	$return=false;
	$_SESSION["key"]=false;
}
if($return) {
	if(pref("language")=="") {$l="en";}
	else {$l=pref("language");}
	require_once("../language/".$l.".php");
	echo "<body style=background:url(../templates/main/images/bg.jpeg);background-size:cover;color:white;font-family:helvetica><br><br><h1 style=text-align:center;>".$text["admin_require"].".</h1><h2 style=text-align:center;><a href=../?inc=login&username=admin style=color:lightblue;text-decoration:none;>&gt; &gt; ".$text["login"]."  &lt; &lt;</a></h2>";return;
}
?>
<body style="background:#eee !important">
<style type="text/css">
.bend {
	animation:rotateY 2.5s ease-out;
	-webkit-animation:rotateY 2.5s ease-out;
}
@keyframes rotateY {
	0%{transform:rotateY(90deg);opacity:0;}
	10%{transform:rotateY(90deg);}
	35%{opacity:1;}
	50%{transform:rotateY(0deg);}
	60%{opacity:1;}
	80%{opacity:0;}
}
@-webkit-keyframes rotateY {
	0%{-webkit-transform:rotateY(90deg);opacity:0;}
	10%{-webkit-transform:rotateY(90deg);}
	35%{opacity:1;}
	50%{-webkit-transform:rotateY(0deg);}
	60%{opacity:1;}
	80%{opacity:0;}
}
</style>
<?php
include("../includes/v.php");
if($_GET["key"]!="") {
	rename("../installation/index.php","../installation/reinstall.php");
	echo "<meta http-equiv=refresh content=1.9,../?inc=login&username=admin>";
}
if($_GET["fallback"]=="all_logs") {
	echo "<meta http-equiv=refresh content=1.9,../includes/all_logs.php>";
}
elseif($_GET["fallback"]=="backup") {
	echo "<meta http-equiv=refresh content=1.9,../admin/?action=11&show=".$_GET["show"].">";
}
if(isset($_GET["latest"])) {
	$latest_timestamp=file("../log/backup_timestamps.log");
	$filename="../backup/".str_replace(PHP_EOL,null,$latest_timestamp[0]).".bckp";
}
elseif($_GET["show"]=="download") {
	$filename="../backup/".substr($_GET["timestamp"],0,(strlen($_GET["timestamp"])-2))."00.bckp";
	@unlink('../files/comis_backup.zip');
	$zip = new ZipArchive;
	$res = $zip->open('../files/comis_backup.zip', ZipArchive::CREATE);
	if ($res === TRUE) {
	    $zip->addFile($filename, "comis-".substr($_GET["timestamp"],0,(strlen($_GET["timestamp"])-2))."00.bckp");
	    $zip->close();
	    echo 'OK [200] '.$filename."\n";
	} else {
	    echo 'Error';
	}
	echo "<meta http-equiv=refresh content='0,".'../files/comis_backup.zip'."'>";
	return;
}
elseif($_GET["show"]=="download_all") {
	$tss=file("../log/backup_timestamps.log");
	unlink('../files/comis_backup.zip');
	foreach($tss as $ts) {
		$newts=substr($ts,0,(strlen($ts)-2))."00";
		if(!in_array($newts,$timestamps)) {
			$timestamps[]=$newts;
		}
	}
	$zip = new ZipArchive;
	$res = $zip->open('../files/comis_backup.zip', ZipArchive::CREATE);
	if ($res === TRUE) {
		foreach($timestamps as $ts) {
			$filename="../backup/".$ts.".bckp";
			$zip->addFile($filename, "comis-".$ts.".bckp");
			$zip->close();
		    echo 'OK [200] '.$filename."\n";
		}
	}
	else
	    echo 'Error';
	echo "<meta http-equiv=refresh content='0,".'../files/comis_backup.zip'."'>";
	return;
}
else {
	if($_GET["file"]!="") {
		$filename="../files/".$_GET["file"]."/comis_exported.bckp";
	}
	elseif($_GET["name"]=="") {
		$filename="../backup/".substr($_GET["timestamp"],0,(strlen($_GET["timestamp"])-2))."00.bckp";
	}
	else {
		$filename="../backup/".$_GET["name"].".bckp";
	}
}
$log.=$filename;
if(file_exists($filename)) {
	$log=$log."file exists.";
	$import=file_get_contents($filename);
	$import_array=file($filename);
	$tmp=explode("#",$import_array[0]);
	$backup_info["version"]=str_replace(PHP_EOL,null,$tmp[2]);
	if(substr($import,1,6)=="backup") {
		$log=$log."\nvalid comis backup file.";
		$log=$log."\nsuppors version ".$backup_info["version"].".";
		$log=$log."\ncurrent version ".$v.".";
		if($v != $backup_info["version"])
			$log=$log."\nWARNING:MIGHT BE NOT COMPATIBLE!";
		else
			$log=$log."\nbackup compatible.";
	}
	else {
		$log=$log."\nERROR:NOT A VALID COMIS BACKUP FILE COMPATIBLE!";
		addlog2("\n".$log);
		$error=true;
	}
	if(!$return) {
		$table_tmp_names=explode("#####?",$import);
		foreach($table_tmp_names as $table_name) {
			if(strstr($table_name,"?#####")) {
				$tmp=explode("?#####",$table_name);
				$table_names[]=$tmp[0];
				$table[$tmp[0]] = explode("+++++",$tmp[1]);
			}
		}
		$log=$log."\nfound ".count($table_names)." tables";
		$log=$log."\nfound some rows";
		foreach($table_names as $table_name) {
			$log=$log."\nstart rebuilding table ".$table_name;
			if(!mysql_query("truncate `".$db_prefix.$table_name."`")) {
				$log=$log."\nWARNING: error by truncating ".$table_name."";
			}
			$i=0;
			foreach($table[$table_name] as $row) {
				$row=str_replace("<", "\<", $row);
				$row=str_replace(">", "\>", $row);
				$row=str_replace("'", "\'", $row);
				if($row!="") {
					$i++;
					$columns=explode("#####",$row);
					if($table_name=="articles") {
						$query="insert into `".$db_prefix.$table_name."`(`id`, `orderid`, `pageid`, `title`, `code`, `editor`, `timestamp`, `public`, `publish_ts`,`page`, `comments`, `views`) values('".$columns[0]."','".$columns[1]."','".$columns[2]."','".$columns[3]."','".$columns[4]."','".$columns[5]."','".$columns[6]."','".$columns[7]."','".$columns[8]."','".$columns[9]."','".$columns[10]."','".$columns[11]."')";
					}
					elseif($table_name=="comments") {
						$query="insert into `".$db_prefix.$table_name."`(`id`, `userid`, `msg`, `timestamp`, `articleid`) values('".$columns[0]."','".$columns[1]."','".$columns[2]."','".$columns[3]."','".$columns[4]."')";
					}
					elseif($table_name=="forum_answers") {
						$query="insert into `".$db_prefix.$table_name."`(`id`, `timestamp`, `answer`, `userid`, `questionid`) values('".$columns[0]."','".$columns[1]."','".$columns[2]."','".$columns[3]."','".$columns[4]."')";
					}
					elseif($table_name=="forum_questions") {
						$query="insert into `".$db_prefix.$table_name."`(`id`, `question`, `image`, `userid`, `timestamp`) values('".$columns[0]."','".$columns[1]."','".$columns[2]."','".$columns[3]."','".$columns[4]."')";
					}
					elseif($table_name=="groups") {
						$query="insert into `".$db_prefix.$table_name."`(`id`, `name`, `edit_articles`, `edit_comments`, `edit_user`, `edit_pages`, `edit_groups`, `edit_newsletter`, `edit_prefs`, `edit_forum`, `edit_shop`, `description`) values('".$columns[0]."','".$columns[1]."','".$columns[2]."','".$columns[3]."','".$columns[4]."','".$columns[5]."','".$columns[6]."','".$columns[7]."','".$columns[8]."','".$columns[9]."','".$columns[10]."','".$columns[11]."')";
					}
					elseif($table_name=="pages") {
						$query="insert into `".$db_prefix.$table_name."`(`id`, `orderid`, `title`, `description`) values('".$columns[0]."','".$columns[1]."','".$columns[2]."','".$columns[3]."')";
					}
					elseif($table_name=="preferences") {
						$query="insert into `".$db_prefix.$table_name."`(`id`, `name`, `value`) values('".$columns[0]."','".$columns[1]."','".$columns[2]."')";
					}
					elseif($table_name=="user") {
						$query="insert into `".$db_prefix.$table_name."`(`id`, `name`, `username`, `email`, `password`, `groupid`, `deactive`, `timestamp`, `last_login`, `icon`, `newsletter`, `md5`, `profilepic`) values('".$columns[0]."','".$columns[1]."','".$columns[2]."','".$columns[3]."','".$columns[4]."','".$columns[5]."','".$columns[6]."','".$columns[7]."','".$columns[8]."','".$columns[9]."','".$columns[10]."','".$columns[11]."','".$columns[12]."')";
					}
					$log=$log."QUERY:".$query;
					if(!mysql_query($query)) {
						$log=$log."\nWARNING: error by inserting row ".$i." into ".$table_name."";
					}
				}
			}
		}
		$log=$log."\n\n".time();
	}
}
else {
	$log=$log."\nERROR:NO FILE FOUND!";
	$error=true;
}
if(!$error) {
	$icon="success";
}
else {
	$icon="failed";
}
addlog2("\n".$log);
echo '<img style="position:fixed;top:43%;left:50%;margin-left:-75px;margin-top:-75px;width:150px;height:150px;" src="../templates/main/images/'.$icon.'.png" alt="" class="bend">';
if($_GET["file"]!="") {
	$glob=glob("../files/".$_GET["file"]."*");
	foreach($glob as $f) unlink($f);
	rmdir("../files/".$_GET["file"]);
}
?>