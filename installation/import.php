<?php session_start(); 
if(file_exists("reinstall.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
?>
<!DOCTYPE html>
<html>
<head>
	<title>COMIS INSTALLATION</title>
	<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="../templates/main/bootstrap.min.css">
	<link rel="stylesheet" href="../templates/main/style.css">
	<link rel="stylesheet" href="../templates/main/install.css">
</head>
<body>
<?php
include("../language/en.php");
if(file_exists("../language/".$_SESSION['l'].".php"))include("../language/".$_SESSION['l'].".php");
	?>
	<div id="installdiv">
		<img src="../images/logo_1000.png" alt width="400">
		<br><br>
			<?php
				$_SESSION["key"]=uniqid().uniqid();
				include("l.php");
				echo '<form enctype="multipart/form-data" style="width:500px;margin-left:-20px;display:block;" action="../bin/import.php?import_db=true	" method="post">';
				echo "<h3 style='color:white;text-shadow:1px 1px 5px hsla(0,0%,0%,.2);'>".$text["please_select_file"].":</h3>
				<input style=display:inline name='file' placeholder='".$text["file"]."' type=file required>
				<input style=width:0;display:inline type=hidden value='".$_SESSION["key"]."' name=key>
				<input style=display:inline;padding:8.5px;width:150px name='upload' value='".$text["install"]."' type='submit'>
				</form><br><br><a href=select_install.php>&larr; go back</a>
				";
			?>
	</div>
</body>