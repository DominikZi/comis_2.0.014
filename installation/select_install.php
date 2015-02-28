<?php session_start(); 
if(file_exists("reinstall.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
if(file_exists("../language/".$_GET["l"].".php"))$_SESSION["l"]=$_GET["l"];
?>
<!DOCTYPE html>
<html>
<head>
	<title>COMIS INSTALLATION</title>
	<link rel="stylesheet" href="../templates/main/bootstrap.min.css">
	<link rel="stylesheet" href="../templates/main/style.css">
	<link rel="stylesheet" href="../templates/main/install.css">
	<style type="text/css">a:hover{font-weight:bold;text-decoration:none;}</style>
</head>
<body>
	<?php
	include("../language/en.php");
	if(file_exists("../language/".$_SESSION['l'].".php"))include("../language/".$_SESSION['l'].".php");
	?>
	<div id="installdiv">
		<img src="../images/logo_1000.png" alt width="400"><br><br>
		<h3 style="margin-top:20px;">
			<a href="setup_db.php">> 
				<?php
				include("l.php");
				echo $text["start_installation"];
				echo "</a><div style=margin:30px;font-size:15px;>";
				echo "<a href=import.php>> ".$text["comis_import"];
				?>
			</a>		
		</h3>
	</div>
</body>
</html>