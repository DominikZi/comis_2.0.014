<!DOCTYPE html>
<html>
<head>
<title><?php show("website_title"); ?></title>
<meta name="copyright" content="">
<?php
echo '
<meta name="keywords" content="'.pref('website_keywords').'">
<meta name="description" content="'.pref('website_description').'">
';
?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<link rel="shortcut icon" href="<?php show('shortcut'); ?>" type="image/x-icon"/>
<link rel="icon" href="<?php show('shortcut'); ?>" type="image/x-icon"/>
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-status-bar-style" content="white">
<link rel="apple-touch-icon" href="<?php show('shortcut'); ?>">
<meta http-equiv="expires" content="0">
<link href="templates/main/style.mobile.css" rel="stylesheet" type="text/css">
<?php
echo '<link href="templates/'.pref('website_template').'/style.css" rel="stylesheet" type="text/css">';
echo '<link href="templates/'.pref('website_template').'/style.mobile.css" rel="stylesheet" type="text/css">';
$result = mysql_query("select * from ".$db_prefix."groups where id = '".$_COOKIE['groupid']."'");
while($row = mysql_fetch_array($result)) {
	$access_pages=$row[edit_pages];
	$access_articles=$row[edit_articles];
	$access_user=$row[edit_user];
	$access_groups=$row[edit_groups];
	$access_comments=$row[edit_comments];
	$access_newsletter=$row[edit_newsletter];
	$access_prefs=$row[edit_prefs];
}
?>
</head>
<body>
<?php
$addon=glob("addons/*.mainpage.mobile.php");foreach($addon as $file)include$file;
?>
<script type=text/javascript src=templates/main/jquery.min.js></script>
<script type="text/javascript">
     $(document).ready(function() {
       $("#refresh").load("refresh.php");
       setInterval(function() {
          $("#refresh").load('refresh.php');
       }, 1000);
    });
</script>
<div id=refresh style="display:none;"></div>	
<div class="container">
<div class="c-header">
<header><h1><?php show("website_title"); ?></h1>
<div id="menutablePHANTOM" style="position:fixed;width:100%;height:100%;top:0;left:0;z-index:-1;"></div>
<table id=menutable width=100% height=100% style="position:absolute;top:0;left:0;z-index:-1;"><tr><td style="vertical-align:top;"><center>
<nav id=menu class="topnav" style="">
	<?php
		if(@$_GET['page']=="" && @$_GET['article']=="" && @$_GET['forum']=="" && @$_GET['shop']=="" && @$_GET['inc']=="") {
			$home = " class='active'";	
			$page = "home";
		} else echo "<style>footer {display:none;}</style>";
		echo "<a onmousedown=fadeOut(); href='.'><li".@$home.">Home</li></a>";	
		if(pref("pages_enabled")=="no") {
			$result=$db->query("select * from ".$db_prefix."articles where title !='' and public = 'yes' order by orderid limit 15");
			if($result->num_rows) {
				while($row = $result->fetch_object()) {
					if(@$_GET['article']==$row->id)	$liactive="class='active'"; else $liactive="";
						echo "<a onmousedown=loadarticle(".$row->id."); href='";echo ($rewrite)?"article":".?article=";echo $row->id."'><li $liactive>".$row->title."</li></a>";
				}
			}
		}
		else {
			$result = $db->query("select * from ".$db_prefix."pages where title !='' order by orderid limit 20");
			if($result->num_rows) {
				while($row = $result->fetch_object()) {
					if(@$_GET['page']==$row->id)	$liactive="class='active'"; else $liactive="";
						echo "<a onmousedown=loadpage(".$row->id."); href='";echo ($rewrite)?"page":".?page=";echo $row->id."'><li $liactive>".$row->title."</li></a>";
				}
			}
			$result = $db->query("select * from ".$db_prefix."articles where page ='yes' and public = 'yes' order by orderid limit 20");
			if($result->num_rows) {
				while($row = $result->fetch_object()) {
					if(@$_GET['article']==$row->id)	$liactive="class='active'"; else $liactive="";
						echo "<a onmousedown=loadarticle(".$row->id."); href='";echo ($rewrite)?"article":".?article=";echo $row->id."'><li $liactive>".$row->title."</li></a>";
				}
			}
		}
		if(pref("forum_enabled")=="yes") {
			if(isset($_GET['forum']))
				echo "<a onmousedown=fadeOut(); href='.?forum=home'><li class='active'>Forum</li></a>";	
			else
				echo "<a onmousedown=fadeOut(); href='.?forum=home'><li>Forum</li></a>";	
		}
		if(pref("shop_enabled")=="yes") {
			if(isset($_GET['shop']))
				echo "<a onmousedown=fadeOut(); href='.?shop=home'><li class='active'>Shop</li></a>";	
			else
				echo "<a onmousedown=fadeOut(); href='.?shop=home'><li>Shop</li></a>";	
		}
		if($_COOKIE['username'] == "") {
			echo "<a href='?inc=login'><li>Login</li></a>";	
		}
		else {
			echo "<a href='?do=logout'><li style=background:#f66>Logout</li></a>";	
		}
		if($_COOKIE['adminaccess'] == "yes" | $_COOKIE['username'] == "admin") {
			echo "<a href='admin'><li style=background:#6f6>zum Admin Center</li></a>";
		}
	?>
	<script type="text/javascript">
//	alert(screen.height+"x"+screen.width);
	</script>
<!--
	<?php
	if($_GET['page'] == "" && $_GET['article'] == "" && $_GET['inc'] == "") {
		$home = " class='active'";	
		$page="home";
	}
	echo "<a href='index.php'><li".$home." style='width:70% !important;padding: 15px 3%;background:lightgray !important;'>Home</li></a>";	
	$result = mysql_query("select * from ".$db_prefix."pages order by id limit 20");
	if($result) {
		while($row = mysql_fetch_array($result)) {
		if($_GET['page'] == $row[id]) {
			echo "<a href='?page=".$row[id]."'><li style='background:cornflowerblue !important;'>".$row[title]."</li></a>";	
		}
		else {
			echo "<a href='?page=".$row[id]."'><li style='background:lightgray !important;'>".$row[title]."</li></a>";	
		}
	}
	}
	if($_COOKIE['username'] == "") {
		echo "<a href='?inc=login'><li style='background:lightgray !important;'>Login</li></a>";	
	}
	else {
		echo "<a href='logout.php'><li onMouseOver=\"this.style.backgroundColor='#f33'\" onMouseOut=\"this.style.backgroundColor='#f66'\" style=background:#f66>Logout</li></a>";	
	}
	if($_COOKIE['adminaccess'] == "yes" | $_COOKIE['username'] == "admin") {
		echo "<a href='admin'><li onMouseOver=\"this.style.backgroundColor='#3f3'\" onMouseOut=\"this.style.backgroundColor='#6f6'\" style=background:#6f6>zum Admin Center</li></a>";
	}	
	?>
	
-->
</nav>
</center></td></tr></table>
</div>
<script type="text/javascript">
function showMenu() {
	if (document.getElementById("menu").style.display=='block') {
		document.getElementById("menu").style.display='none';
		document.getElementById("menutable").style.background='none';
		document.getElementById("menutable").style.zIndex='-1';
		document.getElementById("menutablePHANTOM").style.zIndex='-11111';
		document.getElementById("menutablePHANTOM").style.display='none';
	}
	else {
		document.getElementById("menu").style.display='block';
		document.getElementById("menutablePHANTOM").style.background='hsla(0,0%,0%,.7)';
		document.getElementById("menutablePHANTOM").style.zIndex='11111';
		document.getElementById("menutablePHANTOM").style.display='block';
		document.getElementById("menutable").style.zIndex='111111';
	}
}
</script>
<div style="height:50px;position:absolute;top:20px;right:20px;font-size:40px;z-index:2;"><img onmousedown="showMenu();" src=templates/main/images/menu.png height="100%;"></div>
</header>
<?php if(pref("aside")=="no") { echo "<style>td.articles { width:100% !Important; } td.aside { display: none !Important; } </style>"; } ?>
<?php
	if(isset($_GET['q']))
		include("includes/search.php");	
	elseif(@$page=="home") {
		if(pref("homepage")=="homefeed") {
			include("includes/home.php");	
		}
		elseif(pref("homepage")=="article") {
			$result = $db->query("select * from ".$db_prefix."articles where title='home' or title='HOME' or title = 'Home'");
			if($result->num_rows) {
				while($row = $result->fetch_object()) {
					echo $row->code;
				}
			}
		}
	}
	elseif(isset($_GET['inc']) && file_exists("includes/".$_GET['inc'].".php"))
		include("includes/".$_GET['inc'].".php");	
	elseif(isset($_GET['article'])) {
		$db->query("update `".$db_prefix."articles` set views=views+1 where id = '".$_GET["article"]."'");
		if($_GET["article"]=="8") {
			echo "<h1>Download</h1><br>";
			foreach(glob("files/downloads/comis_*.zip") as $v) {$versions[]=str_replace("files/downloads/comis_","",str_replace(".zip","",$v));}
			$versions=array_reverse($versions);
			$small=false;
			foreach($versions as $v) {
				if($small==false) {
					echo '<a href=files/downloads/comis_'.$v.'.zip class=btn-comis style="clear:both;border:3px solid green !important;background:lightgreen;padding:15px 30px;font-size:30px"><img style=position:absolute;margin-top:0px; src=files/images/14245393132047380297444.png>&emsp;&emsp; Download Comis '.$v.'</a><br>';
				}	
				else {
					echo '<br><br><br><a href=files/downloads/comis_'.$v.'.zip class=btn-comis style="clear:both;border:2px solid green !important;background:lightgreen;padding:10px 20px;font-size:18px"><img style=position:absolute;margin-top:0px;width:28px; src=files/images/14245393132047380297444.png>&emsp;&emsp; Download Comis '.$v.'</a>';
				}					
				$small=true;
			}
			echo '
			<br><br>
			<!--
			<br>Veroffentlicht am 22. Februar 02:47 Uhr<br><br>-->
			<h3>Wie installiere ich Comis?</h3>
			<iframe style="border: 2px solid #333;border-radius:10px" width="425" height="237" src="https://www.youtube.com/embed/1F20Tu3fCzE" frameborder="0" allowfullscreen></iframe>
			';
		}
		else {
			$found=false;
			$result = $db->query("select * from `".$db_prefix."articles` where id = '".$_GET['article']."' order by timestamp desc limit 1");
			if($result->num_rows) {
				while($row = $result->fetch_object()) {
					$arr_article=$row;
				}
				if($arr_article) {
					if($arr_article->public!="yes" && is_admin())
						$not_public=true;
					else
						$not_public=false;
					if($not_public)
						echo "<div class=\"alert alert-danger\">".$text["article_not_public"]."</div>";
					if($arr_article->public=="yes" || is_admin()) {
						echo "<h2 class='article_title'><span>".$arr_article->title."</span> ";
						show_editicons($arr_article->id);
						echo "</h2><div class=pre>".$arr_article->code."</div>";
						if($arr_article->comments=="yes" && pref('pages_enabled')!="no" && pref('comments')!="no")
							include("includes/comments.php");
						if(pref('pages_enabled')!="no")
							echo "<hr class=clear><div class=articlefooter> ".ucfirst($arr_article->editor).", ".date("d.m.y H:i",$arr_article->timestamp).", ".$arr_article->views." ".$text["views"]."  </div>";
						$found=true;
					}
				}
			}
			if(!$found) 
				echo "<h2>".$text["no_article"]."</h2>";
		}
	}
	elseif(isset($_GET['page'])) {
		$found=false;
		$result = $db->query("select * from ".$db_prefix."articles where pageid = '".$_GET['page']."' order by timestamp desc");
		if($result) {
			while($row = $result->fetch_object()) {
				if($found) echo "<hr class=clear>";
				$not_public=($row->public!="yes" && is_admin())?true:false;
				if($not_public)
					echo "<div class=\"alert alert-danger\">".$text["article_not_public"]."</div>";
				if($row->public=="yes" || is_admin()) {
					echo "<h2>".$row->title;
					show_editicons($row->id,$_GET["page"]);
					echo "</h2>
					<div class=pre>".$row->code."</div>
					<br class=clear>
					<a loadarticle(".$row->id."); href='";
					echo ($rewrite)?"article":".?article=";
					echo $row->id."' class='btn-comis'>
					>> ".$text["view_article"]." <<
					</a>
					<br><br>";	
					$found=true;
				}
			}
		}
		if(!$found)
		echo "<h2> ".$text["no_page"]." </h2>";
	}
	elseif(isset($_GET['forum']))include("includes/forum.php");
	elseif(isset($_GET['shop']))include("includes/shop.php");
/*
if($page == "home") {
	include("includes/home.php");	
}
elseif(isset($_GET['inc'])) {
	include("includes/".$_GET['inc'].".php");	
}
elseif(isset($_GET['article'])) {
$found=false;
$result = mysql_query("select * from ".$db_prefix."articles where id = '".mysql_real_escape_string($_GET['article'])."' and public = 'yes' order by timestamp desc");
if($result) {
	while($row = mysql_fetch_array($result)) {
		echo "<h2>".$row[title]." ";
		if($_COOKIE['username'] == "admin" | $access_articles=="yes") {
			echo "&nbsp;<a href=admin/?action=2&a=edit&id=".$row[id]."><img src=templates/main/images/edit.png alt='' style=height:27px;margin-top:-7px;margin-bottom:-5px;></a>
			<a href='admin/?action=2&a=delete&id=".$row[id]."' onmouseup=\"alert('Wollen Sie wirklich den Beitrag `".$row[title]."` l&ouml;schen?');\"><img src=templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:0px;margin-bottom:-5px;></a>";	
		}		
		echo "</h2><p>".$row[code]."</p>";
		if($row['comments'] == "yes") {
			include("includes/comments.php");
		}		
		echo "<hr><div class=articlefooter> ".$text["editor"]." : ".ucfirst($row[editor])." -  ".$text["date"]." : ".date("d.m.y H:i",$row[timestamp])." -  ".$text["views"]." : ".$row[views]." </div>";	
		if(!mysql_query("update ".$db_prefix."articles set views = views+1 where id = '".$_GET['article']."'")) {
		echo "<h1>Error by views + 1</h1>";		
		}	
		$found=true;
	}
}

if(!$found) {echo "<h2>".$text["no_article"]."</h2>";}
}
elseif(isset($_GET['page'])) {
$found=false;
$result = mysql_query("select * from ".$db_prefix."articles where pageid = '".mysql_real_escape_string($_GET['page'])."' and public = 'yes' order by timestamp desc");
if($result) {
	while($row = mysql_fetch_array($result)) {
		echo "<h2>".$row[title]." ";

		if($_COOKIE['username'] == "admin" | $access_articles=="yes") {
			echo "&nbsp;<a href=admin/?action=2&a=edit&id=".$row[id]."><img src=templates/main/images/edit.png alt='' style=height:27px;margin-top:-7px;margin-bottom:-5px;></a>
			<a href='admin/?action=2&a=delete&id=".$row[id]."' onmouseup=\"alert('Wollen Sie wirklich den Beitrag `".$row[title]."` l&ouml;schen?');\"><img src=templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:0px;margin-bottom:-5px;></a>";	
		}		
		
		echo "</h2><p>".$row[code]."</p><a href='?article=".$row[id]."' class='btn-comis'> ".$text["view_article"]." </a><br><hr>";	
	}
		$found=true;
}
if(!$found) {echo "<h2> ".$text["no_article"]." </h2>";}
}*/

?>
<br style="clear:both;" />
</div>
<footer style="width:180px !important;padding:5px 0px;border-radius:5px;">made with <a href=//domiscms.de>comis</a></footer>
</body>
</html>