<?php session_start();
if(!file_exists("index.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
$_SESSION['adminaccess'] = false;
$_SESSION['groupid'] = false;
$_SESSION['username'] = false;
$_SESSION['userid'] = false;
//logout
//echo '<iframe src="../?do=logout"></iframe>';
?>
<!DOCTYPE html>
<html>
<head>
	<title>COMIS INSTALLATION</title>
	<link rel="stylesheet" href="../templates/main/bootstrap.min.css">
	<script type="text/javascript" src="../templates/main/jquery.min.js"></script>
	<link rel="stylesheet" href="../templates/main/style.css">
	<link rel="stylesheet" href="../templates/main/install.css">
	<script type="text/javascript">
	function dbcheck() {$("#dbcheck").load("dbcheck.php?db_host="+$("#db_host").val()+"&db_username="+$("#db_username").val()+"&db_name="+$("#db_name").val()+"&db_password="+$("#db_password").val());}
	</script>
</head>
<body onload="dbcheck();setInterval(function(){dbcheck()},500);">
	<?php
		$_SESSION['website_title'] = $_POST['website_title'];
		$_SESSION['website_description'] = $_POST['website_description'];
		$_SESSION['user_name'] = $_POST['user_name'];
		$_SESSION['user_email'] = $_POST['user_email'];
		$_SESSION['admin_passwd1'] = $_POST['admin_passwd1'];
		$_SESSION['timezone'] = $_POST['timezone'];
		$_SESSION['ads'] = $_POST['ads'];
		$_SESSION['forum_enabled'] = $_POST['forum_enabled'];
		$_SESSION['shop_enabled'] = $_POST['shop_enabled'];
		$_SESSION['create_placeholders'] = $_POST['create_placeholders'];
		
include("../language/en.php");
if(file_exists("../language/".$_SESSION['l'].".php"))include("../language/".$_SESSION['l'].".php");
	?>
	<div style="margin:auto;width:600px;margin-top:90px;">
		<?php 
		include_once("../lib/import_function.php");
		include_once("../lib/global_functions.php");
		$db_preconf=import_comis("../etc/db.conf");
		echo '
		<h1 style=text-align:center;color:white;>'.$text['0'].'</h1>
		</center>
		<hr style=width:500px;>
		<form style="width:500px;margin:auto;" role="form" method="post" action="setup_install.php">
			<p style="color:white;font-style:italic;text-align:justify">'.$text['32'].'</p>
		  <div class="form-group" title="'.$text["34"].'">
			<label>'.$text['10'].'</label>
		    <input type="text" class="form-control" value="'.$db_preconf["db_host"].'"  id="db_host" name="db_host" placeholder="'.$text['10'].'">
		  </div>
		  <div class="form-group" title="'.$text["35"].'">
			<label>'.$text['11'].'</label>
		    <input type="text" class="form-control" value="'.$db_preconf["db_username"].'" autocomplete="off" id="db_username" name="db_username" placeholder="'.$text['11'].'">
		  </div>
		  <div class="form-group">
			<label>'.$text['12'].'</label>
		    <input type="password" class="form-control" value="" autocomplete="off"  id="db_password" name="db_password" placeholder="'.$text['12'].'">
		  </div>
		  <div class="form-group">
			<label>'.$text['13'].'</label>
		    <input type="text" class="form-control" value="'.$db_preconf["db_name"].'" id="db_name" name="db_name" placeholder="'.$text['13'].'">
		  </div>
		  <div class="form-group">
			<label>DB-PREFIX (Nur Buchstaben und Zahlen, Optional)</label>
		    <input type="text" class="form-control" value="'.str_replace("_","",$db_preconf["db_prefix"]).'" name="db_prefix" placeholder="DB-PREFIX (Optional)">
		  </div>
		  <div id="dbcheck">
			  <button type="submit" class="btn btn-default">'.$text['8'].'</button>
		  </div>
		</form>
		<hr style=width:500px;>
		</body>
		';
		?>
	</div>
</body>