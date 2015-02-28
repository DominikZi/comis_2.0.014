<?php
$dont_change_on_auto_update=false;
if(conf("memory_get_peak_usage"))memory_get_peak_usage();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php
			echo "<title>".pref("website_title")."</title>\n\t\t";
			echo '<meta name="keywords" content="'.pref('website_keywords').'">'."\n\t\t";
			echo '<meta name="description" content="'.pref('website_description').'">'."\n";
		?>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
		<meta http-equiv="content-style-type" content="text/css">
		<link rel="shortcut icon" href="<?php show('shortcut'); ?>" type="image/x-icon"/>
		<link href="templates/main/style.css" rel="stylesheet" type="text/css">
		<script src="templates/main/jquery.min.js" type="text/javascript"></script>
		<?php
		if(conf("speed_method")=="jquery")
			echo '
			<script type="text/javascript">
			function loadarticle(id) {
				$(".article").load("includes/loadpage.php?article="+id);
				history.pushState(0,0,".?article="+id);
			}
			function loadpage(id) {
				$(".article").load("includes/loadpage.php?page="+id);
				history.pushState(0,0,".?page="+id);
			}
			</script>';
		else
			echo '<script type="text/javascript">function loadarticle(id) {}function loadpage(id) {}</script>';
		echo(file_exists('templates/'.pref('website_template').'/style.css'))?'<link href="templates/'.pref('website_template').'/style.css" rel="stylesheet" type="text/css">':'<link href="templates/main/style.css" rel="stylesheet" type="text/css">';
		echo "<style>";
		if(file_exists(pref("headpicture")) && pref("headpicture_yn")!="no") {
			if(pref("headpicture")!="images/headpicture.jpeg") $important=" !important";
			echo "#forum-menu,#shop-menu li{background:hsla(0,0%,0%,.3) !important;}.topnav{margin-bottom:10px;}.headpicture h1{padding:45px 40px;text-shadow:-2px -2px 22px white,-2px 2px 22px white,2px -2px 22px white,2px 2px 22px white;}.headpicture{height:240px;margin:0px -41px;margin-bottom:-50px;margin-top:-72px;border-radius:5px 5px 0px 0px;background-image:url('".pref("headpicture")."')".$important.";box-shadow: inset 0px 0px 30px -10px hsla(0,0%,0%,.6);background-position:center bottom;background-size:cover;}.blur{-webkit-filter: blur(6px); -moz-filter: blur(6px); -o-filter: blur(6px); -ms-filter: blur(6px); filter: blur(6px);}.headpicture.blur{margin:1%;margin-top:55px;height:50px;position:relative;z-index:1;box-shadow:0px 0px 0px;background-position:center bottom;}.topnav {position:relative;z-index:1;}";
		}
		else
			echo ".headpicture h1{padding:0;margin:30px;margin-top:45px;margin-bottom:10px;font-size:36px;margin-left:-25px;color:#444}.topnav li{margin-top:-7px}.topnav{width:100%;padding:15px 40px;margin-left:-40px;background:hsla(0,0%,0%,.2);}.headpicture {background-image:none !important;}";
		echo ".topnav li{box-shadow:0px 0px 8px 0px hsla(0,0%,0%,.1),inset 0px 0px 10px 0px hsla(0,0%,100%,.25);}";
		if(file_exists(pref("background")) && pref("background_yn")!="no")
			echo "body{background-image:url('".pref("background")."');background-attachement:fixed;}";
		if(pref("aside")=="no")echo "td.articles {width:100% !important;} td.aside {display: none !important;}";
		if(pref("aside")=="no")echo ".article{width:95%;}";
		echo "div.container,footer{width:".pref("width_container").";}.magic-area input {cursor: pointer;}.magic-area div.info{display:none;}";
		if(conf("magic_area_info"))echo".magic-area:hover div:first-child {background-color:white;border-radius: 5px 5px 0px 0px;border-bottom: 10px solid white !important;margin-bottom: -10px;}.magic-area div.info {cursor: default;background-color:white;position: absolute;margin-top:2px;width: 200px;z-index: 99999999999999;text-align: justify;border-radius: 0px 5px 5px 5px;display: none;border:none;opacity: 0;padding-left: 10px;background-image: none;}.magic-area:hover div.info {opacity: 1;display: block;}";
		if(conf("magic_recognized_stylesheets"))foreach(glob("files/stylesheets/*.css") as $f)include($f);
		echo "</style>";
		if(pref("animations")=="yes") {
			echo "\n\t\t<script>\n\t\tfunction fadeOut() {\n\t\t\t$('body').fadeOut(500);\n\t\t}\n\t\tfunction fadeIn() {\n\t\t\t$('body').hide(0);\n\t\t\t$('body').fadeIn(500);\n\t\t}\n\t\t</script>\n\t\t";	
			sleep(.65);
		}
		else {
			echo "\n\t\t<script>\n\t\tfunction fadeOut() {\n\t\t}\n\t\tfunction fadeIn() {\n\t\t}\n\t\t</script>";
		}
		echo '<script>$(document).ready(function(){/*do st.*/});</script>';
		echo "\n\t</head>\n\t<body onload=\"fadeIn();\">";
		$addon=glob("addons/*.mainpage.php");foreach($addon as $file)include$file;
		$result = $db->query("select * from ".$db_prefix."groups where id = '".$_COOKIE['groupid']."'");
		if($result->num_rows)
			while($row = $result->fetch_object()) {
				$access_pages=$row->edit_pages;
				$access_articles=$row->edit_articles;
				$access_user=$row->edit_user;
				$access_groups=$row->edit_groups;
				$access_comments=$row->edit_comments;
				$access_newsletter=$row->edit_newsletter;
				$access_prefs=$row->edit_prefs;
			}
		echo "\n\t".'<script type="text/javascript">
		$(document).ready(function() {
			$("#refresh").load("bin/refresh.php");
			setInterval(function() {
				$("#refresh").load(\'bin/refresh.php\');
			}, ';
		echo(is_admin())?3:50;
		echo "000);\n\t\t\t});\n\t</script>";
		echo "\n\t\t\t<div id=refresh style=\"display:none;\"></div>
		<div class=\"container\">
		<div class=\"c-header\">";
				echo "\n\t\t\t<div id=\"searchform\">\n\t\t\t\t<form";
				if(conf("modrewrite"))
					echo " action='search'";
				echo ">\n\t\t\t\t\t<div id=\"login\">";
				if(@$_COOKIE['username']=="")
					echo "<a onmousedown=fadeOut(); href='.?inc=login'>Login</a>";	
				else {
					echo "<a onmousedown=fadeOut(); href='.?inc=account'>".ucfirst($_COOKIE['username'])."</a>";	
					echo "<a onmousedown=fadeOut(); href='.?inc=logout'>Logout</a>";	
				}
				if(@$_COOKIE['adminaccess']=="admin_like")
					echo "<a onmousedown=fadeOut(); href='.?do=back_to_admin'>".$text["back_to_admin"]."</a>";
				if(is_admin())
					echo "<a onmousedown=fadeOut(); href='admin'>".$text["adminarea"]."</a>";
				echo "</div>";
				
				if(pref('search')=="yes") {
					echo '<input id=search name=q placeholder="'.$text['search'].'" required><button type=submit id=searchbtn>
					<img src=templates/main/images/searchbtn.png></button>
					';
				}
				echo "</form></div>";
				$result=$db->query("select * from ".$db_prefix."articles where public = 'no' &&  publish_ts < ".time()." &&  publish_ts != 0 order by orderid limit 15");
				if($result->num_rows) {
					while($row = $result->fetch_object()) {
						$db->query("update ".$db_prefix."articles set public = 'yes' where id = '".$row->id."'");
						$db->query("update ".$db_prefix."articles set publish_ts = '0' where id = '".$row->id."'");
					}
				}
			?>
				<div class="pagepicture"></div>
				<header>
					<div class="headpicture">
						<h1>
						<?php show("website_title"); if(is_sudo()) echo " <a class='changeopacity icon' onmousedown=fadeOut(); href='admin/?action=9&dropdown=1&view_next=true&active_field=website_title'><img src=templates/main/images/edit.png alt='' style=height:23px;margin-top:-7px;margin-bottom:-2px;></a>"; ?>
						</h1>
						<?php if(pref("headpicture")!="" && pref("headpicture_yn")!="no")	echo "<div class='blur headpicture'></div>"; ?>
					</div>
					<nav class="topnav">
						<?php
							if(@$_GET['page']=="" && @$_GET['article']=="" && @$_GET['forum']=="" && @$_GET['shop']=="" && @$_GET['inc']=="") {
								$home = " class='active'";	
								$page = "home";
							}
							echo "<a onmousedown=fadeOut(); href='.'><li".@$home.">Home</li></a>";	
							if(pref("pages_enabled")=="no") {
								$result=$db->query("select * from ".$db_prefix."articles where title !='' and public = 'yes' order by orderid limit 15");
								if($result->num_rows) {
									while($row = $result->fetch_object()) {
										if(@$_GET['article']==$row->id)	$liactive="class='active'"; else $liactive="";
											echo "<a onmousedown=loadarticle(".$row->id."); href='";echo (conf("modrewrite"))?"article":".?article=";echo $row->id."'><li $liactive>".$row->title."</li></a>";
									}
								}
							}
							else {
								$result = $db->query("select * from ".$db_prefix."pages where title !='' order by orderid limit 20");
								if($result->num_rows) {
									while($row = $result->fetch_object()) {
										if(@$_GET['page']==$row->id)	$liactive="class='active'"; else $liactive="";
											echo "<a onmousedown=loadpage(".$row->id."); href='";echo (conf("modrewrite"))?"page":".?page=";echo $row->id."'><li $liactive>".$row->title."</li></a>";
									}
								}
								$result = $db->query("select * from ".$db_prefix."articles where page ='yes' and public = 'yes' order by orderid limit 20");
								if($result->num_rows) {
									while($row = $result->fetch_object()) {
										if(@$_GET['article']==$row->id)	$liactive="class='active'"; else $liactive="";
											echo "<a onmousedown=loadarticle(".$row->id."); href='";echo (conf("modrewrite"))?"article":".?article=";echo $row->id."'><li $liactive>".$row->title."</li></a>";
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
							if(@$_GET['inc']=="login")
								$login = " class='active'";	
						?>
					</nav>
				</header>
			</div>
		<br><br>
		<?php
		if(is_admin()) {
			echo "<div class='addcontent'>";
			if(is_sudo() || $access_articles=="yes") {
				echo "<span class='magic-area'><div style='padding-left:8px;background-image:url(templates/main/images/magic.gif)'>&emsp;</div>
				<form action='bin/article_from_file.php' method='post' style='display:inline;' enctype='multipart/form-data'>
				<input title='' type='file' name='file' id='file' style='opacity:0;width:20px;margin-left:-46px;margin-right:-2px;' onchange='this.form.submit();'>
				<input type='hidden' name='pageid' value='".$_GET["page"]."'>
				</form><div class=info>".$text["magic_area_info"]."</div></span>";
			}
			if(pref(pages_enabled)=="yes" && (is_sudo() || $access_pages=="yes"))
				echo "<a href='admin/?action=1&dropdown=0&view_next=true'><div>".$text["page"]."</div></a>\n";
			if(is_sudo() || $access_articles=="yes")
				echo "<a href='admin/?action=2&dropdown=0&page=".$_GET["page"]."&view_next=true'><div>".$text["article"]."</div></a>\n";
			if(is_sudo() || $access_user=="yes")
				echo "<a href='admin/?action=3&dropdown=0&view_next=true'><div>".$text["user"]."</div></a>\n";
			if(is_sudo() || $access_prefs=="yes")
				echo "<a href='admin/?action=9&view_next=true'><div class='preferences-icon'>".$text["preferences"]."</div></a>\n";
				echo "<a href='.?inc=account'><div class='preferences-icon'>".$text["account"]."</div></a>\n";
				echo "</div>";
		}
		elseif(is_user()) {
			echo "<div class='addcontent'>\n";
			echo "<a href='.?inc=account'><div class='preferences-icon'>".$text["account"]."</div></a>\n";
			echo "</div>";
		}
		?>
		<br class="clear">
		<div class=article>
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
										echo "</h2><div class=pre>".$arr_article->code."</div><br class=clear>";
										if($arr_article->comments=="yes" && pref('pages_enabled')!="no" && pref('comments')!="no")
											include("includes/comments.php");
										if(pref('pages_enabled')!="no")
											echo "<hr class=clear><div class=articlefooter> ".$text["editor"]." : ".$arr_article->editor." -  ".$text["date"]." : ".date("d.m.y H:i",$arr_article->timestamp)." -  ".$text["views"]." : ".$arr_article->views." </div>";
										$found=true;
									}
								}
							}
							if(!$found) 
								echo "<h2>".$text["no_article"]."</h2>";
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
										echo (conf("modrewrite"))?"article":".?article=";
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
					?>
				</div>
				<div class="aside">
					<?php if(pref("aside")!="no")include("includes/aside.php");?>
				</div>
			<br class="clear">
		</div>
		<footer>
			<?php	echo ucfirst($text["made_with"]); ?>
			<a href="http://domiscms.de" style="text-decoration:underline">COMIS</a>
			<?php if(pref('user_name')!="") echo $text["by"]." ".ucfirst(pref("user_name")); ?>
		</footer>
	</body>
</html>