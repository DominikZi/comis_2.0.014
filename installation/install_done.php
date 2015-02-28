<?php session_start(); 
if(!file_exists("index.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
$_SESSION["rename"]=true;
?>
<head>
<title>COMIS INSTALLATION</title>
<link rel="stylesheet" href="../templates/main/bootstrap.min.css">
<link rel="stylesheet" href="../templates/main/style.css">
<link rel="stylesheet" href="../templates/main/install.css">
<style type="text/css">a {color:white;}a:hover{/*text-shadow:0px 0px 10px black;*/color:blue;text-decoration: none;}</style>
</head><body>
<?php 
include("../language/en.php");
if(file_exists("../language/".$_SESSION['l'].".php"))include("../language/".$_SESSION['l'].".php"); ?>
<div style="background:hsla(0,0%,100%,.1);position:fixed;top:0;left:0;width:100%;height:100%;">
<div style="margin:5%;height:500px;">
<center>
<?php
if(isset($_GET["install"])) {
	echo '
	<h1>'.$text['20'].'!</h1>
	</center>
	<div style="width:500px;margin:auto;text-align:justify">
	<hr style="width:500px;"><h3>
	';
	echo $text['33'].'<br><br><center><a href="../">&lt;&lt;'.$text['22'].'&gt;&gt;</a>&emsp;&emsp;<a href="../?inc=login&username=admin">&lt;&lt;'.ucfirst($text['login']).'&gt;&gt;</a></center><br><br>'.$text['21'].'<br><span class="center">'.$text['36'].'</span></h3></p>';
	echo '
	<hr style=width:500px;>
	</body>';
}
else {
	echo '
	<h1>'.$text['27'].'</h1>
	</center>
	<div style="width:500px;margin:auto;">
	<hr style=width:500px;><center><h3>'.$text['28'].'! <br><br><a href=../>'.$text['28'].'</a>
	<hr style=width:500px;>
	</body>
	';
}
?>