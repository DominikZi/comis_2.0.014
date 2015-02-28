<?php session_start(); 
if(!file_exists("index.php") && $_COOKIE['username']!="admin" && $_COOKIE['adminaccess'] != "yes" && $_COOKIE['adminaccess'] != "admin_like") die("please login as administrator.");
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
$inst_conf = "#preflist
website_title=".$_POST['website_title']."
website_description=".$_POST['website_description']."
user_name=".$_POST['user_name']."
user_email=".$_POST['user_email']."
timezone=".str_replace(PHP_EOL,null,$_POST['timezone'])."
create_placeholders=".$_POST['create_placeholders']."
forum_enabled=".$_POST['forum_enabled']."
shop_enabled=".$_POST['shop_enabled']."
timestamp=".time();
rename("../etc/inst.conf","../etc/inst.conf.ori");
file_put_contents("../etc/inst.conf", $inst_conf);
?><head>
<title>COMIS INSTALLATION</title>
<link rel="stylesheet" href="../templates/main/bootstrap.min.css">
<link rel="stylesheet" href="../templates/main/style.css">
<link rel="stylesheet" href="../templates/main/install.css">
</head><body>
<?php 
include("../language/en.php");
if(file_exists("../language/".$_SESSION['l'].".php"))include("../language/".$_SESSION['l'].".php"); ?>
<div style="background:hsla(0,0%,100%,.1);position:fixed;top:0;left:0;width:100%;height:100%;">
<div style="margin:5%;height:500px;">
<center>
<?php echo '
<h1 style=color:white;>'.$text['0'].'</h1>
</center>
<hr style=width:500px;>';
echo '
<form style="width:500px;margin:auto;" role="form" method="post" action="install.php">
  <label>'.$text['40'].'</label>
   <div class="form-group">
    <input type="text" class="form-control" autocomplete="off" name="sec_question" placeholder="'.$text['41'].'">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" autocomplete="off" name="sec_answer" placeholder="'.$text['42'].'">
  </div>
	<div id="passwdcompare">
	</div>  
  <button type="submit" class="btn btn-default">'.$text['8'].'</button>
  <button type="submit" class="btn btn-warning">'.$text['skip'].'</button>
</form>';
echo '
<hr style=width:500px;>
</body>
';
?>