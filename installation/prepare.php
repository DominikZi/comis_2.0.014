<?php session_start();
if(!file_exists("index.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
$_POST["db_host"]=$_SESSION["db_host"];
$_POST["db_username"]=$_SESSION["db_username"];
$_POST["db_password"]=$_SESSION["db_password"];
$_POST["db_name"]=$_SESSION["db_name"];
$_POST["db_prefix"]=$_SESSION["db_prefix"];
 ?>
<head>
<title>COMIS INSTALLING</title>
<link rel="stylesheet" href="../templates/main/bootstrap.min.css">
<link rel="stylesheet" href="../templates/main/style.css">
<link rel="stylesheet" href="../templates/main/install.css">
<style type="text/css">
<style type="text/css">
.err
{
	text-shadow: 1px 1px 1px #333;
	color: white !important;
}
.err a
{
	color: #fb0;
}
.err a:hover
{
	color: orange;
}
*
{
	color: white;
}
.rotatefast
{
	margin-top: 15px;
	animation: rotate .7s infinite linear;
	-webkit-animation: rotate .7s infinite linear;
	-moz-animation: rotate .7s infinite linear;
	-ms-animation: rotate .7s infinite linear;
	-o-animation: rotate .7s infinite linear;
}
@keyframes rotateright {from{transform:rotateZ(0deg);}to{transform:rotateZ(360deg);}}
@-webkit-keyframes rotate {from{-webkit-transform:rotateZ(0deg);}to{-webkit-transform:rotateZ(360deg);}}
@-moz-keyframes rotate {from{-moz-transform:rotateZ(0deg);}to{-moz-transform:rotateZ(360deg);}}
@-ms-keyframes rotate {from{-ms-transform:rotateZ(0deg);}to{-ms-transform:rotateZ(360deg);}}
@-o-keyframes rotate {from{-o-transform:rotateZ(0deg);}to{-o-transform:rotateZ(360deg);}}
</style>
<?php
$data = "
sec_question=".$_POST['sec_question']."
sec_answer=".strtolower($_POST['sec_answer'])."";
file_put_contents("../etc/inst.conf", file_get_contents("../etc/inst.conf").$data);
if($_SESSION['l']=="de") {
	echo "
	<script>
	function start() {
		setTimeout(function() {document.getElementById('msg').innerHTML='Installation starten...'},0);
		setTimeout(function() {document.getElementById('msg').innerHTML='Datenbank-Verbindung checken...'},300);
		setTimeout(function() {document.getElementById('msg').innerHTML='Datenbanken checken...'},600);
		setTimeout(function() {document.getElementById('msg').innerHTML='Datenbanken mit Infos f&uuml;ttern...'},1200);
		setTimeout(function() {document.getElementById('msg').innerHTML='Homepage vorbereiten...'},1600);
		setTimeout(function() {document.getElementById('msg').innerHTML='Admin Center vorbereiten...'},1900);
		setTimeout(function() {document.getElementById('msg').innerHTML='Testinhalte einf&uuml;gen...'},2000);
		setTimeout(function() {document.getElementById('msg').innerHTML='Login Session erstellen...'},2600);
		setTimeout(function() {document.getElementById('msg').innerHTML='Setzte Zeitzone...'},2900);
		setTimeout(function() {document.getElementById('msg').innerHTML='Erstelle User...'},3100);
		setTimeout(function() {document.getElementById('msg').innerHTML='Erstelle Gruppen...'},3300);
		setTimeout(function() {document.getElementById('msg').innerHTML='Erstelle Seiten...'},3600);
		setTimeout(function() {document.getElementById('msg').innerHTML='Erstelle Artikel...'},3800);
		setTimeout(function() {document.getElementById('msg').innerHTML='Setzte Einstellungen...'},4100);
		setTimeout(function() {document.getElementById('msg').innerHTML='Installation abschliessen...'},4300);
	}
	</script>";
}
else {
	echo "
	<script>
	function start() {
		setTimeout(function() {document.getElementById('msg').innerHTML='starting Installation...'},0);
		setTimeout(function() {document.getElementById('msg').innerHTML='checking databases...'},300);
		setTimeout(function() {document.getElementById('msg').innerHTML='filling databases...'},1200);
		setTimeout(function() {document.getElementById('msg').innerHTML='preparing homepage...'},1600);
		setTimeout(function() {document.getElementById('msg').innerHTML='preparing Admin Center...'},1900);
		setTimeout(function() {document.getElementById('msg').innerHTML='injecting content...'},2000);
		setTimeout(function() {document.getElementById('msg').innerHTML='creating login session...'},2600);
		setTimeout(function() {document.getElementById('msg').innerHTML='setting timezone...'},2900);
		setTimeout(function() {document.getElementById('msg').innerHTML='creating users...'},3100);
		setTimeout(function() {document.getElementById('msg').innerHTML='creating groups...'},3300);
		setTimeout(function() {document.getElementById('msg').innerHTML='creating pages...'},3600);
		setTimeout(function() {document.getElementById('msg').innerHTML='creating articles...'},3800);
		setTimeout(function() {document.getElementById('msg').innerHTML='setting preferences...'},4100);
		setTimeout(function() {document.getElementById('msg').innerHTML='finishing installation...'},4500);
	}
	</script>";
	
}
?>
</head><body onload="start();">
<?php 
include("../language/en.php");
if(file_exists("../language/".$_SESSION['l'].".php"))include("../language/".$_SESSION['l'].".php"); ?>
<div style="background:hsla(0,0%,100%,.1);position:fixed;top:0;left:0;width:100%;height:100%;">
<?php
echo "<!--";if(mysql_connect($_POST['db_host'],$_POST['db_username'],$_POST['db_password'])) {
	if(mysql_select_db($_POST['db_name'])) {
	}
	else {
	echo "<!---->";
		echo "<center><br><br><br><h1 class=err>".$text['14']."</a>";
		return;
	}
}
else {
	echo "<!---->";
	echo "<center><br><br><br><h1 class=err>".$text['14']."</a>";
	return;
}
echo "<!---->";
$daten = "#preflist
db_host=".$_SESSION['db_host']."
db_username=".$_SESSION['db_username']."
db_password=".$_SESSION['db_password']."
db_name=".$_SESSION['db_name']."
db_prefix=".$_SESSION['db_prefix']."
timestamp=".time()."
";
$dateihandle = fopen("../etc/db.conf","w");
if(fwrite($dateihandle, $daten))
{
	echo "
<div style=margin:5%;height:500px;>
<center>
<h1 style=color:white;>".$text['0']."</h1>
</center>
<hr style=width:500px;><div style='margin:5%;margin-top:0;height:500px;'><center><p style=width:500px;text-align:justify;>
<meta http-equiv=refresh content=0,install.php><center><h3 id=msg>".$text['15']." </h3><a href=install_done.php><img alt src=images/load.png width=50 class=rotatefast></a>
</body>";
	return;
}
else {
	echo '
<div style=margin:5%;height:500px;>
<center>
<h1 style=color:white;>'.$text['0'].' </h1>
</center>
<hr style=width:500px;><div style="margin:5%;margin-top:0;height:500px;"><center><p style=width:500px;text-align:justify;>'.$text['16'].'</p><textarea class=form-control  style=width:500px;height:135px;overflow:hidden;letter-spacing:1px;color:#222>'.$daten.'</textarea></p><p style=width:500px;text-align:left;margin-left:4px;margin-top:-7px;color:gray;	>'.$text['17'].': db.conf</p><p style=width:500px;text-align:justify;>'.$text['18'].'</p><br><center>
<a href=install.php class="btn btn-default">'.$text['19'].'</a><br><br><center><iframe src=excheck.php frameborder=0 style=height:50px;width:400px;></iframe><br><br>
';
	return;
}
?>
</body>