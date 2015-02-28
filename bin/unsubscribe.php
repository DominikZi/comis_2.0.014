<?php
include('db.php');
echo "<!--";
if(isset($_GET['md5'])) {
	if(mysql_query("update ".$db_prefix."user set `newsletter` = 'no' where `md5` = '".$_GET['md5']."'")) {
		mysql_query("update ".$db_prefix."user set `md5` = '". md5(rand(1,99999)) ."' where `md5` = '".$_GET['md5']."'");
		echo "-->Sie wurden erfolgreich vom Newsletter abgemeldet.";
	}
	else {
		echo "-->Fehler bei der Newsletterabmeldung.";
	}
}
?>