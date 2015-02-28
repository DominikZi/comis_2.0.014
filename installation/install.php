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
echo "<!--";
include("../db.php");
echo "<!---->";
if($db->query("CREATE TABLE IF NOT EXISTS `".$db_prefix."articles` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `orderid` int(255) NOT NULL,
  `pageid` int(255) NOT NULL,
  `title` varchar(500) COLLATE latin1_german1_ci NOT NULL,
  `code` varchar(55000) COLLATE latin1_german1_ci NOT NULL,
  `editor` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `timestamp` int(255) NOT NULL,
  `public` varchar(3) COLLATE latin1_german1_ci NOT NULL DEFAULT 'yes',
  `publish_ts` int(255) COLLATE latin1_german1_ci NOT NULL DEFAULT '0',
  `page` varchar(3) COLLATE latin1_german1_ci NOT NULL DEFAULT '0',
  `comments` varchar(3) COLLATE latin1_german1_ci NOT NULL DEFAULT 'yes',
  `views` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ")) {
$db->query("truncate `".$db_prefix."articles`");
$db->query("truncate `".$db_prefix."user`");
$db->query("truncate `".$db_prefix."pages`");
$db->query("truncate `".$db_prefix."preferences`");
$db->query("truncate `".$db_prefix."comments`");
$db->query("truncate `".$db_prefix."groups`");
$db->query("truncate `".$db_prefix."forum_answers`");
$db->query("truncate `".$db_prefix."forum_questions`");
$db->query("truncate `".$db_prefix."shop_items`");
$db->query("truncate `".$db_prefix."shop_useritems`");
$db->query("truncate `".$db_prefix."shop_categories`");
$db->query("
CREATE TABLE IF NOT EXISTS `".$db_prefix."user` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `username` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `email` varchar(500) COLLATE latin1_german1_ci NOT NULL,
  `password` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `groupid` int(255) NOT NULL,
  `deactive` varchar(3) COLLATE latin1_german1_ci NOT NULL DEFAULT 'no',
  `timestamp` int(255) NOT NULL,
  `last_login` int(255) NOT NULL,
  `icon` varchar(5000) COLLATE latin1_german1_ci NOT NULL,
  `newsletter` varchar(3) COLLATE latin1_german1_ci NOT NULL DEFAULT 'yes',
  `md5` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `profilepic` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ");
$db->query("insert into `".$db_prefix."user`(`name`, `username`, `password`, `email`, `md5`) values('".$_SESSION['user_name']."','admin','".md5($_SESSION['admin_passwd1'])."', '".$_SESSION['user_email']."', '".md5(rand(1,99999999999999999999)).uniqid()."')");

$create=true;
include("create.php");
$db->query("ALTER TABLE `".$db_prefix."articles` ADD FULLTEXT(`code`)");
$db->query("ALTER TABLE `".$db_prefix."comments` ADD FULLTEXT(`msg`)");
$install = true;}
if($_SESSION['l']=="de")
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('welcome_msg','Herzlich Willkommen !')");
else
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('welcome_msg','Welcome !')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('website_title','".$_SESSION['website_title']."')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('website_description','".$_SESSION['website_description']."')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('user_name','".$_SESSION['user_name']."')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('user_email','".$_SESSION['user_email']."')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('website_template','icecube')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('width_container','900')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('admin_template','rainbow')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('aside','yes')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('search','yes')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('animations','no')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('ads','".$_SESSION['ads']."')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('forum_enabled','".$_SESSION['forum_enabled']."')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('shop_enabled','".$_SESSION['shop_enabled']."')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('pages_enabled','yes')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('currency','&euro;')");
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('language','".$_SESSION['l']."')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('welcome_img','')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('welcome_img_yn','yes')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('headpicture_yn','yes')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('headpicture','images/headpicture.jpeg')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('background_yn','no')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('background','')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('scalefeature','yes')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('homepage','homefeed')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('comments','yes')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('profilepics','yes')");	
$db->query("insert into `".$db_prefix."preferences`(`name`, `value`) values('shortcut','images/favicon.ico')");	
$db->query("insert into `".$db_prefix."articles`(`title`, `code`) values('".$text["contact"]."','".$_SESSION["user_name"]."<br><a href=\"mailto:".$_SESSION["user_email"]."\">".$_SESSION["user_email"]."</a>')");
$result=$db->query("select * from `".$db_prefix."user` where username='admin'");
while($row=$result->fetch_object()) {$admin_acc=true;}
if(!$admin_acc) {echo "<br><br><br><h1 style=\"text-align:center;\">ADMIN ACCOUNT WAS NOT CREATED! ERROR!</h1><br><br><br>";$install=false;}
if($install) {echo "<meta http-equiv=refresh content=0,install_done.php?install>";}
else {echo "<meta http-equiv=refresh content=0,install_done.php>";}
?>