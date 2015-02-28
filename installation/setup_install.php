<?php session_start();
if(file_exists("reinstall.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
$_POST['db_prefix']=str_replace("_", "", $_POST['db_prefix']);
$_POST['db_prefix']=str_replace(".", "", $_POST['db_prefix']);
?><head>
<title>COMIS INSTALLATION</title>
<script type="text/javascript" src="../templates/main/jquery.min.js"></script>
<link rel="stylesheet" href="../templates/main/bootstrap.min.css">
<link rel="stylesheet" href="../templates/main/style.css">
<link rel="stylesheet" href="../templates/main/install.css">
</head><body>
<div style="margin:auto;width:600px;margin-top:90px;">
<?php 
include("../language/en.php");
if(file_exists("../language/".$_SESSION['l'].".php"))include("../language/".$_SESSION['l'].".php");
echo '
<script type="text/javascript">
$(document).ready(passwdcompare());
function passwdcompare() {
	if($("#admin_passwd1").val()!="") {
		if ($("#admin_passwd1").val()==$("#admin_passwd2").val()) {
			$("#passwdcompare").html("<div style=float:right;margin-top:-8px;margin-bottom:8px;color:white>'.$text["38"].'</div>");
			$("#continuebtn").html(\'<button type="submit" class="btn btn-success">'.$text['8'].'</button>\');
		}
		else {
			$("#passwdcompare").html("<div style=float:right;margin-top:-8px;margin-bottom:8px;color:#faa;font-weight:bold>'.$text["37"].'</div>");
			$("#continuebtn").html(\'<button type="submit" class="btn btn-default">'.$text['8'].'</button>\');
		}
	}
	setTimeout(function(){passwdcompare();},400);
}
</script>';
?>
<?php 		
include_once("../lib/import_function.php");
require_once("../lib/global_functions.php");
if(!@mysql_connect($_etc['db_host'],$_POST['db_username'],$_POST['db_password']) && !@mysql_select_db($_POST['db_name'])) {
	echo "<center><br><p style=font-size:200px;color:white;transform:rotateZ(80deg);-webkit-transform:rotateZ(80deg)>:(</p><br><h2 style=position:relative;z-index:9999; class=err>".$text['14']."</a>";
	return;
}
$_SESSION["db_host"]=$_POST["db_host"];
$_SESSION["db_username"]=$_POST["db_username"];
$_SESSION["db_password"]=$_POST["db_password"];
$_SESSION["db_name"]=$_POST["db_name"];
if($_POST['db_prefix'] != "" && strlen($_POST['db_prefix']) < 128 && !strstr($_POST['db_prefix'],"'") && !strstr($_POST['db_prefix']," ") && !strstr($_POST['db_prefix'],"/") && !strstr($_POST['db_prefix']," * ") && strlen($_POST['db_prefix']) > 1) {
	$_POST['db_prefix']=str_replace("-", "", $_POST['db_prefix']);
	$_SESSION['db_prefix']=$_POST['db_prefix'];
}
else {
	$_SESSION['db_prefix']=substr(md5(rand(1,999999)), rand(1,5), 8);
}
$db_conf = "#preflist
db_host=".$_POST['db_host']."
db_username=".$_POST['db_username']."
db_password=".$_POST['db_password']."
db_name=".$_POST['db_name']."
db_prefix=".$_SESSION['db_prefix']."
timestamp=".time()."
";
file_put_contents("../etc/db.conf", $db_conf);
$install_preconf=import_comis("../etc/inst.conf");
$avaiable_timezones=import_comis("../etc/avaiable_timezones.list");
echo '
<center>
<h1 style=color:white;>'.$text['0'].'<h1>
</center>
<hr style=width:500px;>
<form style="width:500px;margin:auto;" role="form" method="post" action="setup_secquest.php">
  <div class="form-group">
<label>'.$text['1'].'</label>
    <input type="text" class="form-control" name="website_title" placeholder="'.$text['2'].'" value="'.$install_preconf["website_title"].'">
  </div>
  <div class="form-group">
    <textarea type="text" class="form-control" name="website_description" placeholder="'.$text['3'].'">'.$install_preconf["website_description"].'</textarea>
  </div>
  <label>'.$text['4'].'</label>
  <div class="form-group">
    <input type="text" class="form-control" name="user_name" id="user_name" placeholder="'.$text['5'].'" value="'.$install_preconf["user_name"].'">
  </div>
  <div class="form-group">
    <input type="email" class="form-control" autocomplete="off" name="user_email" placeholder="'.$text['6'].'" value="'.$install_preconf["user_email"].'">
  </div>
  <div class="form-group">
    <input type="password" class="form-control" autocomplete="off" name="admin_passwd1" id="admin_passwd1" placeholder="'.$text['7'].'" required>
  </div>
  <div class="form-group">
    <input type="password" class="form-control" autocomplete="off" name="admin_passwd2" id="admin_passwd2" placeholder="'.$text['7'].' '.$text['repeat'].'" required>
  </div>
  <div id="passwdcompare"></div>
  ';
  if($_SESSION['l'] == "de") {
	  echo '
	  	<label>Zeitzone</label>
	  	<div class="form-group">
	  	<select name="timezone">';
		foreach($avaiable_timezones as $timezone) {
      	if(str_replace(PHP_EOL,null,$install_preconf["timezone"])==str_replace(PHP_EOL,null,$timezone)) {
	     		echo '<option selected value="'.$timezone.'">'.$timezone.'</option>';
     		}
      	else {
	     		echo '<option value="'.$timezone.'">'.$timezone.'</option>';
     		}
		}
      echo '
	  	</select>
	  	</div>
	 	<label>Beispielinhalte installieren (empfohlen)</label>
  		<input type=checkbox value=yes ';if($install_preconf["create_placeholders"]=="yes")echo "checked";echo ' name=create_placeholders><br>
	 	<label>Diskussions-Forum Plugin aktivieren</label>
  		<input type=checkbox value=yes ';if($install_preconf["forum_enabled"]=="yes")echo "checked";echo ' name=forum_enabled><br>
	 	<label>Shop Plugin aktivieren</label>
  		<input type=checkbox value=yes ';if($install_preconf["shop_enabled"]=="yes")echo "checked";echo '  name=shop_enabled><br>';
	}
	else {
	  echo '
	  	<label>Timezone</label>
	  	<div class="form-group">
	  	<select name="timezone">';
		foreach($avaiable_timezones as $timezone) {
      	if(str_replace(PHP_EOL,null,$install_preconf["timezone"])==str_replace(PHP_EOL,null,$timezone)) {
	     		echo '<option selected value="'.$timezone.'">'.$timezone.'</option>';
     		}
      	else {
	     		echo '<option value="'.$timezone.'">'.$timezone.'</option>';
     		}
		}
      echo '
	  	</select>
	  	</div>
	 	<label>Install Examplecontent (recommended)</label>
  		<input type="checkbox" value="yes" ';if($install_preconf["create_placeholders"]=="yes")echo "checked";echo ' name="create_placeholders"><br>
	  	<label>Enable discussion-forum plugin</label>
  		<input type="checkbox" value="yes" ';if($install_preconf["forum_enabled"]=="yes")echo "checked";echo ' name="forum_enabled"><br>
	  	<label>Enable shop plugin</label>
  		<input type="checkbox" value="yes" ';if($install_preconf["shop_enabled"]=="yes")echo "checked";echo ' name="shop_enabled"><br>
  		';
	}
	
  echo '<br>
  <div id="continuebtn"><button type="submit" class="btn btn-default">'.$text['8'].'</button></div>
</form>
<hr style=width:500px;>
</body>
';
?>