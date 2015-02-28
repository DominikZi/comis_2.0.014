<?php
if(!file_exists("index.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");?>
<!DOCTYPE html>
<html>
<head>
	<title>COMIS INSTALLATION</title>
	<meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="../templates/main/bootstrap.min.css">
	<link rel="stylesheet" href="../templates/main/style.css">
	<link rel="stylesheet" href="../templates/main/install.css">
	<style type="text/css">#sprache{transition:.5s;}#sprache img{transition:.5s;margin:0px;width:300px;}#sprache img:hover{box-shadow: 0px 0px 10px;margin:-20px;width:340px;}</style>
</head>
<body>
<?php
include_once("../lib/global_functions.php");
if(isset($_GET["abort"])) {rename("index.php","reinstall.php");reload_page();}
?>
	<a href="?abort" style="display:block;"><img src="../templates/main/images/delete.png" style="z-index:999;position:fixed;bottom:5px;left:10px;bottom:10px;opacity:.9;display:block;width:30px;height:30px" alt="">&emsp;</a>
	<div id="body">
		<table id="sprache" width=100% height=100% style="position:fixed;top:0;left:0;z-index:2;">
			<tr>
				<td>
					<center>
						<a href="select_install.php?l=de">
							<img onmouseout="document.getElementById('selectedl').innerHTML='Bitte eine Sprache ausw&auml;hlen<br><span style=font-size:23px;opacity:.7;><i>Please select Language</i></span>';document.getElementById('en').style.opacity='1';" onmouseover="document.getElementById('selectedl').innerHTML='Deutsch als Sprache setzen<br><br>';document.getElementById('en').style.opacity='.3';"  id=de src="images/de.jpeg">
						</a>
						
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						
						<a href="select_install.php?l=en">
							<img onmouseout="document.getElementById('selectedl').innerHTML='Bitte eine Sprache ausw&auml;hlen<br>	<span style=font-size:23px;opacity:.7;><i>Please select Language</i></span>';document.getElementById('de').style.opacity='1';" onmouseover="document.getElementById('selectedl').innerHTML='Set English as language<br><br>';document.getElementById('de').style.opacity='.3';" id=en src="images/en.jpeg">
						</a>
						<br><br><br><br><br><br><br><br><br><br>
						<form style="font-size:4.5mm;" action="select_install.php" method="get">
						<?php echo $text['pref_l']; ?>
						<br>
						<select onchange="this.form.submit()" name="l">
							<option>Please select Language</option>
							<?php
							require_once("../lib/import_function.php");
							$avaiable_languages=import_comis("../etc/avaiable_languages.conf");
							if ($lfilehandle = opendir('../language/')) {
							    while (false !== ($lfile = readdir($lfilehandle))) {
							        if ($lfile != "." && $lfile != ".." && !strstr($lfile,"~")) {
								        	$tmp=explode(".",$lfile);
								        	if($avaiable_languages[$tmp[0]]!="") {
												echo '<option value="'.$tmp[0].'">'.$avaiable_languages[$tmp[0]].'</option>';
											}
							        }
							    }
							    closedir($handle);
							}
							?>
						</select>
						</form>
					</center>
				</td>
			</tr>
		</table>
		<table width=100% height=100% style="position:fixed;top:0;left:0;z-index:1;">
			<tr>
				<td>
					<center>
						<br><br><br><br><br><br><br><br><br><br>
						<h2 id="selectedl">
						Bitte eine Sprache ausw&auml;hlen<br><span style=font-size:23px;opacity:.7;><i>Please select Language</i></span>
						</h2>
					</center>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>