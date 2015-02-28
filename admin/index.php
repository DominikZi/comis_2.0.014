<?php session_start();?>
<!DOCTYPE html>
<html>
<head>
<?php
	$adminaccess = @$_COOKIE['adminaccess'];
	require_once('../db.php');
	require_once("../lib/admin_functions.php");
	if(is_file("../etc/exha.conf")) {
		if(isset($_GET["exha"]))
			$_SESSION["exha"]=@$_GET["exha"];
			$exha=import_comis("../etc/exha.conf");
	}
	if(isset($_SESSION["exha"]))
		if(md5(strtoupper($_SESSION["exha"]))==$exha["code"])
			$adminaccess = "admin_like";
	if(is_admin()) {
		include('../includes/v.php');
		if(pref("language")=="") {$l="en";}
		else {$l=pref("language");}
		$l=(file_exists("../language/".$l.".php"))?$l:"en";include_once("../language/".$l.".php");
	}
	else {
		if(pref("language")=="") {$l="en";}
		else {$l=pref("language");}
		require_once("../language/".$l.".php");
		echo "<body style=background:url(../templates/main/images/bg.jpeg);background-size:cover;color:white;font-family:helvetica><br><br><h1 style=text-align:center;>".$text["admin_require"].".</h1><h2 style=text-align:center;><a href=../?inc=login&username=admin style=color:lightblue;text-decoration:none;>&gt; &gt; ".$text["login"]."  &lt; &lt;</a></h2>";return;
	}
	
	$preview_target=false;
	$auto_backup=true;
	$auto_backup_notification=false;
	$cget=true;
	
	if(!file_exists("../backup/".substr(time(),0,strlen(time())-2)."00.bckp") && $auto_backup) {
		if($auto_backup_notification) echo "<a href=?action=11>
		<div class='changeopacity notification'>
		<img src=../templates/main/images/icon_backup.gif style=margin-bottom:-7px;margin-top:-2px;margin-right:10px;>
		".$text["backup_was_made"]."
		</div>		
		</a>";
		include_once('../bin/auto_backup.php');
	}
	if(conf("night_version") && (date("H") >= 1 && date("H") <= "6" ) || date("H")=="23") $nacht = true;
	if(conf("night_version") && @$_GET["night"]=="1" || @$_GET["night"]=="0")
		$_SESSION["night"]=@$_GET["night"];	
	if(conf("night_version") && isset($nacht) && !isset($_SESSION["night"])) {
		?>
		<script type="text/javascript">
		if (confirm("Es scheint Nacht zu sein! Aus gesunheitlichen Gründen sollten Sie, um Ihre Augen zu schonen, in den Nachtmodus von Comis wechseln! Möchten Sie das jetzt tun?")) {
			window.location.href="?night=1";
		}
		else {
			window.location.href="?night=0";
		}
		</script>
		<?php
	}
	if(conf("night_version") && @$_SESSION["night"]=="1") {
		echo "<style>body {background:#001 !important;opacity:.7;}</style>";
	}
	
	if(@$_SESSION['mobile']===true) {
		$included=true;
		require("mobile.php");
		return;
	}
	echo "<title>".pref("website_title")." - ".$text['a_dmin']."</title>"; ?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8">
<meta http-equiv="content-style-type" content="text/css">
<link rel="shortcut icon" href="../<?php show('shortcut'); ?>" type="image/x-icon"/>
<link href="../templates/main/style.css" rel="stylesheet" type="text/css">
<?php echo '<link href="../templates/'.pref('admin_template').'/style.css" rel="stylesheet" type="text/css">';?>
<script type="text/javascript" src="../templates/main/jquery.min.js"></script>
<link href="../templates/main/jquery-te.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../templates/main/jquery-te.min.js"></script>
<script type="text/javascript">$(document).ready(function(){$(".htmleditor").jqte();});</script>
<script src="http://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
<script>
$(function() {
$.datepicker.formatDate( "yy.mm-dd", new Date( 2007, 1 - 1, 26 ));
$( ".datepicker" ).datepicker();
});
</script>


</head>
<body id="admin" style="background-attachment:fixed;">
<?php
	if($cget && !$_SESSION["cget_empty"]) {
		if(@file_get_contents("http://domiscms.de/comis_info.php?desktop")=="")
			$_SESSION["cget_empty"]=true;
		else
			echo @file_get_contents("http://domiscms.de/comis_info.php?desktop");
	}
	include("../includes/noscript.php");
	$result = $db->query("select * from `".$db_prefix."groups` where id = '".$_COOKIE['groupid']."'");
	while($row = $result->fetch_assoc()) {
		$access_pages=$row[edit_pages];
		$access_articles=$row[edit_articles];
		$access_user=$row[edit_user];
		$access_groups=$row[edit_groups];
		$access_comments=$row[edit_comments];
		$access_newsletter=$row[edit_newsletter];
		$access_prefs=$row[edit_prefs];
		$access_forum=$row[edit_forum];
		$access_shop=$row[edit_shop];
	}
	if($_COOKIE['username']=="admin" || $adminaccess=="admin_like") {
		$access_pages="yes";
		$access_articles="yes";
		$access_user="yes";
		$access_groups="yes";
		$access_comments="yes";
		$access_newsletter="yes";
		$access_prefs="yes";
		$access_forum="yes";
		$access_shop="yes";
	}
?>
<div id=adminheader>
	<img src="../images/logo2.png" style="position:fixed;top:15px;opacity:.7;left:10px;height:40px;" onclick="goathail()">
	<center>
		<h1>
			<?php echo $text['adminheader']; ?>
		</h1>
	</center>
</div>
<div id="leftnav">
	<?php
	if(pref("forum_enabled")=="yes" && pref("shop_enabled")=="yes") {
		$forumandshop=$text['forum']." & ".$text['shop'];
	}
	elseif(pref("forum_enabled")=="yes") {
		$forumandshop=$text['forum'];
	}
	elseif(pref("shop_enabled")=="yes") {
		$forumandshop=$text['shop'];
	}
	$dont_show_menu = array(10);
	$menu = array($text['current'],
		$text['pages'],
		$text['articles'],
		$text['user'],
		$text['groups'],
		$text['comments'],
		$text['newsletter'],
		$forumandshop,
		$text['addons'],
		$text['preferences'],
		$text['updates'],
		$text['backup']);
	if($_COOKIE['username']=="admin") {
		for($x=0;$x < count($menu);$x++) {
			if($menu[$x]!="") {
				if(@$_GET['action']==$x || (@$_GET['parent_action']==$x && @$_GET["parent_action"]!="")) {
					echo "<a href=?action=".$x."&".ucfirst($menu[$x])."><li class=active>".ucfirst($menu[$x])."</li></a>";
						if(@$_GET['action']==1 && pref("pages_enabled")!="no")
							echo "<a href=?action=".$x."&dropdown=0><li class=dropdown>".$text['new_page']."</li></a>";			
						if(@$_GET['action']==2)
							echo "<a href=?action=".$x."&dropdown=0><li class=dropdown>".$text['new_article']."</li></a>";			
						if(@$_GET['action']==3)
							echo "<a href=?action=".$x."&dropdown=0><li class=dropdown>".$text['new_user']."</li></a>";			
						if(@$_GET['action']==4)
							echo "<a href=?action=".$x."&dropdown=0><li class=dropdown>".$text['new_group']."</li></a>";			
						if(@$_GET['action']==7) {
							if(pref("forum_enabled")=="yes") {
								if(pref("forum_enabled")=="yes" && pref("shop_enabled")=="yes") {
									echo "<a href=?action=".$x."&dropdown=10><li class=dropdown>".$text['forum']."</li></a>";		
									$li = " - ";
								}
								echo "<a href=?action=".$x."&dropdown=11><li class=dropdown>$li".$text['forum_threads']."</li></a>";	
								echo "<a href=?action=".$x."&dropdown=12><li class=dropdown>$li".$text['new_question']."</li></a>";	
							}
							if(pref("shop_enabled")=="yes") {
								if(pref("forum_enabled")=="yes" && pref("shop_enabled")=="yes")
									echo "<a href=?action=".$x."&dropdown=20><li class=dropdown>".$text['shop']."</li></a>";	
								echo "<a href=?action=".$x."&dropdown=21><li class=dropdown>$li".$text['shop_show_items']."</li></a>";			
								echo "<a href=?action=".$x."&dropdown=22><li class=dropdown>$li".$text['shop_new_item']."</li></a>";			
								echo "<a href=?action=".$x."&dropdown=23><li class=dropdown>$li".$text['shop_show_categories']."</li></a>";			
								echo "<a href=?action=".$x."&dropdown=24><li class=dropdown>$li".$text['shop_new_categorie']."</li></a>";			
							}
						}
						if(@$_GET['action']==8 || @$_GET['parent_action']==8) {
							echo "<a href=?action=".$x."&dropdown=0><li class='dropdown'>".$text['my_addons']."</li></a>";			
							echo "<a href=?action=".$x."&dropdown=1><li class='dropdown'>".$text['install_addon_from file']."</li></a>";			
						}
						if(@$_GET['action']==9 || @$_GET['parent_action']==9) {
							echo "<a href=?action=".$x."&dropdown=0&Webseite><li class=dropdown>".$text['website']."</li></a>";			
							echo "<a href=?action=".$x."&dropdown=1&Pers&ouml;nlich><li class=dropdown>".$text['personaly']."</li></a>";			
							echo "<a href=?action=".$x."&dropdown=2><li class=dropdown>".$text['comisbrowser']." [BETA]</li></a>";			
							if(file_exists("../installation/reinstall.php")) {	
								echo "<a href=../installation/reinstall.php style=color:#a00;><li class=dropdown>".$text['reinst']."</li></a>";			
							}
						}
						if(@$_GET['action']==11) {
							echo "<a href=?action=11&show=all><li class=dropdown>".$text["backup_all"]."</li></a>
							<a href=?action=11&show=daily><li class=dropdown>".$text["backup_daily"]."</li></a>
							<a href=?action=11&show=download><li class=dropdown>".$text["download_backups"]."</li></a>
							<a href=?action=11&show=my_backups><li class=dropdown>".$text["my_backups"]."</li></a>
							<a href=?action=11&show=create_backup><li class=dropdown>".$text["create_backup"]."</li></a>
							<a href=../bin/backup.php?export><li class=dropdown>".ucfirst($text["export"])."</li></a>
							<a href=?action=11&show=import><li class=dropdown>".ucfirst($text["import"])."</li></a>";
						}
				}
				elseif($x==1 && pref("pages_enabled")=="no") {}
				elseif(!in_array($x,$dont_show_menu))
					echo "<a href=?action=".$x."&".ucfirst($menu[$x])."><li>".ucfirst($menu[$x])."</li></a>";
			}
			if($x==7)
				echo '<br>';
		}
	}
	else {
		if($access_pages=="yes") {	
			if(@$_GET['action']==1) {
				echo "<a href=?action=1><li class=active>".ucfirst($menu[1])."</li></a>";
				echo "<a href=?action=1&dropdown=0&NeueSeite><li class=dropdown>".$text['new_page']."</li></a>";			
			}
			else {
				echo "<a href=?action=1><li>".ucfirst($menu[1])."</li></a>";
			}
		}
		if($access_articles=="yes") {	
			if(@$_GET['action']==2) {
				echo "<a href=?action=2><li class=active>".ucfirst($menu[2])."</li></a>";
				echo "<a href=?action=2&dropdown=0&NeuerBeitrag><li class=dropdown>".$text['new_article']."</li></a>";				
			}
			else {
				echo "<a href=?action=2><li>".ucfirst($menu[2])."</li></a>";
			}
		}
		if($access_user=="yes") {	
			if(@$_GET['action']==3) {
				echo "<a href=?action=3><li class=active>".ucfirst($menu[3])."</li></a>";
				echo "<a href=?action=3&dropdown=0&NeuerBenutzer><li class=dropdown>".$text['new_user']."</li></a>";		
			}
			else
				echo "<a href=?action=3><li>".ucfirst($menu[3])."</li></a>";
		}
		if($access_groups=="yes") {	
			if(@$_GET['action']==4) {
				echo "<a href=?action=4><li class=active>".ucfirst($menu[4])."</li></a>";
				echo "<a href=?action=4&dropdown=0&NeueGruppe><li class=dropdown>Neue Gruppe</li></a>";		
			}
			else
				echo "<a href=?action=4><li>".ucfirst($menu[4])."</li></a>";
		}
		if($access_comments=="yes") {	
			if(@$_GET['action']==5)
				echo "<a href=?action=5><li class=active>".ucfirst($menu[5])."</li></a>";
			else
				echo "<a href=?action=5><li>".ucfirst($menu[5])."</li></a>";
		}
		if($access_newsletter=="yes") {	
			if(@$_GET['action']==6)
				echo "<a href=?action=6><li class=active>".ucfirst($menu[6])."</li></a>";
			else
				echo "<a href=?action=6><li>".ucfirst($menu[6])."</li></a>";
		}
		if($access_forum=="yes" || $access_shop=="yes") {
			if(@$_GET['action']==7) {
				echo "<a href=?action=7><li class=active>".ucfirst($menu[7])."</li></a>";
				if($access_forum=="yes") {
					echo "<a href=?action=7&dropdown=10><li class=dropdown>".$text['forum']."</li></a>";		
					echo "<a href=?action=7&dropdown=11><li class=dropdown> - ".$text['forum_threads']."</li></a>";		
					echo "<a href=?action=7&dropdown=12><li class=dropdown> - ".$text['new_question']."</li></a>";		
				}
				if($access_shop=="yes") {	
					echo "<a href=?action=7&dropdown=20><li class=dropdown>".$text['shop']."</li></a>";			
					echo "<a href=?action=7&dropdown=21><li class=dropdown> - ".$text['shop_show_items']."</li></a>";			
					echo "<a href=?action=7&dropdown=22><li class=dropdown> - ".$text['shop_new_item']."</li></a>";			
					echo "<a href=?action=7&dropdown=23><li class=dropdown> - ".$text['shop_show_categories']."</li></a>";			
					echo "<a href=?action=7&dropdown=24><li class=dropdown> - ".$text['shop_new_categorie']."</li></a>";		
				}	
			}
			else {
				echo "<a href=?action=7><li>".ucfirst($menu[7])."</li></a>";
			}
		}
		echo "<br>";
		if($access_prefs=="yes") {
			if(@$_GET['action']==9) {
				echo "<a href=?action=9><li class=active>".ucfirst($menu[9])."</li></a>";
				echo "<a href=?action=9&dropdown=0&Webseite><li class=dropdown>".$text['website']."</li></a>";
				if(file_exists("../installation/reinstall.php")) {	
					echo "<a href=../installation/reinstall.php style=color:#f55;><li class=dropdown>".$text['reinst']."</li></a>";
				}			
			}
			else {
				echo "<a href=?action=9><li>".ucfirst($menu[9])."</li></a>";
			}
		}
	}
	if($l=="de" && conf("disp_help_link")) echo "<a href='http://domiscms.de/help'><li>".$text["help"]."</li></a>";
	?>
	<a href="../?do=logout">
		<li style="position:fixed;top:13px;right:20px;width:70px;text-align:center;"><?php echo ucfirst($text['logout']); ?></li>
	</a>
	<a href=?action=100><li <?php if(@$_GET["action"]==100) echo "class=active"; ?> style="position:fixed;top:13px;right:120px;width:100px;text-align:center;" >Information</li></a>
	<?php
	if(@$_SESSION["night"]=="1" && conf("night_version")) {
	?>
	<a href=?night=0&action=<?php echo @$_GET["action"]; ?>&dropdown=<?php echo @$_GET["dropdown"]; ?>><li style="position:fixed;top:13px;right:250px;width:70px;text-align:center;" ><?php echo $text["lighten"]; ?></li></a>
	<?php
	}
	elseif(conf("night_version")) {
	?>
	<a href=?night=1&action=<?php echo @$_GET["action"]; ?>&dropdown=<?php echo @$_GET["dropdown"]; ?>><li style="position:fixed;top:13px;right:250px;width:70px;text-align:center;" ><?php echo $text["dark"]; ?></li></a>
	<?php
	}
	$dbconf=import_comis("../etc/db.conf");
	if($_SERVER["SERVER_NAME"]=="localhost" && conf("admin_mysql_link")) {
		echo '<a href="/phpmyadmin?db='.$dbconf["db_name"].'&table='.$dbconf["db_prefix"].'_articles" target=_blank><li style="position:fixed;top:13px;right:';
		if(conf("night_version"))echo 350;else echo 250;//250
		echo 'px;width:70px;text-align:center;" >MySQL</li></a>';
	}
	?>
	<a href="../" target="<?php echo $preview_target; ?>">
		<li style="position:fixed;left:0;bottom:0;width:200px;" ><?php echo ucfirst($text['website_preview']); ?></li>
	</a>
</div>
<div id="admincontainer" style="padding:50px;overflow:auto;">
<?php
if(@$_GET['action']==0)
	echo "<style>#latest td {opacity:.7;border:0px solid lightgray;border-radius:10px;}</style><table id=latest width=100%; height=100%;>
	<tr height=50% style='height:50% !Important;vertical-align:top;'>
		<td width=50%; height=50%><iframe class=tile id=iframe1 width=100%  frameborder=0 style=height:100%; src=//domiscms.de/updates.php?v=".$v."&l=".$l."&source=iframe><a href=//domiscms.de/updates.php?v=".$v."&l=".$l."&source=iframe>click here</a></iframe></td>
		<td width=50%; height=50%><iframe class=tile id=iframe2 width=100%  frameborder=0 style=height:100%; src=//domiscms.de/dashboard.php?v=".$v."&l=".$l."&hide_header&source=iframe><a href=//domiscms.de/dashboard.php?v=".$v."&l=".$l."&source=iframe>click here</a></iframe></td>
	</tr>
	<tr>
		<td width=50%; height=50%><iframe class=tile id=iframe3 width=100%  frameborder=0 style=height:100%;overflow:hidden; src=../includes/all_comments.php></iframe></td>
		<td width=50%; height=50%><iframe class=tile id=iframe4 width=100%  frameborder=0 style=height:100%; src=../includes/all_logs.php?l=".$l."></iframe></td>
	</tr>	
	</table>";
if(@$_GET['action']==1 && $access_pages=="yes") {
	if(@$_GET["a"]=="move_up") {
		$db->query("update ".$db_prefix."pages set orderid=orderid+1 where id = '".$_GET['id']."'");
	}
	elseif(@$_GET["a"]=="move_down") {
		$db->query("update ".$db_prefix."pages set orderid=orderid-1 where id = '".$_GET['id']."'");
	}
	if(@$_GET['dropdown']=="0") {
		$hide=true;
		if($_POST['submit']== $text['save'] ) {
			if($db->query("insert into ".$db_prefix."pages(`title`,`description`) values('".$_POST['title']."','".$_POST['description']."')")) {
				echo "<div class='alert alert-success'>".$text["page_created"]."</div>";
				addlog("Seite ".$_POST['title']." erstellt.");
			}
			else {
				echo "<div class='alert alert-danger'>".$text["error"].".</div>";
				addlog("FEHLER: Seite ".$_POST['title']." nicht erstellt.");
			}
			$hide=false;
		}
		else
			echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'" method=post>'.$text['new_page']."</h2>".$text['title'].'<br>
			<input name="title"  placeholder="'. $text['title_of_page'] .'">
			<br><br>'.$text['description'].'<br>
			<input name="description" placeholder="'. $text['optional'] .'">
			<br><br>
			<input name="submit" value="'. $text['save'] .'" type="submit"><br></form>';			
	}
	if(@$_GET['a']=="edit") {
		if($_POST['editsubmit']==$text[save]) {
			if($db->query("update ".$db_prefix."pages set `title` = '".$_POST['title']."', `description` = '".$_POST['description']."' where id = '".@$_GET['id']."'")) {
				echo "<div class='alert alert-success'>".$text["changes_saved"]."</div>";
				addlog("Seite ".$_POST['title']." bearbeitet.");
			}
			else {
				echo "<div class='alert alert-danger'>".$text["error"]."</div>";
				addlog("FEHLER: Seite ".$_POST['title']." nicht bearbeitet.");
			}
		}
		else {
			echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&id='.@$_GET['id'].'&a=edit" method=post>';
			?>
			<h2><?php echo $text["edit_page"]; ?></h2>
			<?php echo $text["title"]; ?><br>
			<?php
			$result = $db->query("select * from ".$db_prefix."pages where id = '".@$_GET['id']."'");
			while($row = $result->fetch_assoc()) {
				echo "
			<input name='title' value='".$row[title]."' placeholder='".$text['title_of_page']."'>
			<br><br>
			". $text['description'] ."<br>
			<input name='description' value='".$row[description]."' placeholder='".$text['optional']."'>
			<br><br>	
			<input name='editsubmit' value='".$text[save]."' type='submit'>
			<br>
			";	
			}
			?>
			</form>
			<?php
		}
	}
	if(!$hide) {
		$result = $db->query("select * from ".$db_prefix."pages order by id desc");
		if($result) {
			if(@$_GET['a']=="delete") {
				if($db->query("delete from ".$db_prefix."pages where id = '".@$_GET['id']."'")) {
					echo "<div class='alert alert-success'>".$text["page_deleted"]."</div>";
					addlog("Seite '".@$_GET['id']."'  entfernt.");
				}
				else {
					echo "<div class='alert alert-danger'>".$text["page_deleted_err"]."</div>";
					addlog("FEHLER: Seite '".$_POST['id']."' nicht entfernt.");
				}
			}
			if(@$_GET['a']=="edit" && $_POST['editsubmit'] != $text["save"]) {}
			else {
				echo "<table width=100% class='table'>";
				echo "<tr>
				<th style=border:0;width:40px>ID</th>
				<th style=border:0;width:50px>OID</th>
				<th style=border:0;width:30%>". $text['name'] ."&nbsp;</th>
				<th style=border:0;width:40%>". $text['description'] ."&nbsp;</th>
				<th style=border:0;text-align:right;padding-right:50px;width:12%>". $text['actions'] ."&nbsp;</th>
				</tr>";
				$result = $db->query("select * from ".$db_prefix."pages order by id desc");
				while($row = $result->fetch_assoc()) {
					echo "<tr>
					<td>".$row[id]."&nbsp;</td>
					<td>".$row[orderid]."&nbsp;</td>
					<td>".$row[title]."&nbsp;</td>
					<td>".$row[description]."&nbsp;</td>
					<td>";
					$i++;
					echo "
					<a href='?action=".@$_GET['action']."&a=move_down&id=".$row[id]."' style=outline:none;><img title='Move down' src=../templates/main/images/move_down.png alt='' style=height:27px;margin:-7px;margin-left:7px;float:right></a>";
					echo "
					<a href='?action=".@$_GET['action']."&a=move_up&id=".$row[id]."' style=outline:none;><img title='Move up' src=../templates/main/images/move_up.png alt='' style=height:27px;margin:-7px;margin-left:7px;margin-right:-2px;float:right></a>";
					echo "
					<a href='../?page=".$row[id]."'  style=outline:none;float:right><img src=../templates/main/images/preview.png title='Preview' alt='' style=height:27px;margin:-7px;margin-left:10px;margin-right:-2px;></a>
					<a href='?action=".@$_GET['action']."&a=delete&id=".$row[id]."' onmouseup=\"alert('".$text['shure_article']."');\"  style=outline:none;float:right><img title='Delete' src=../templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:14px;margin-right:-2px;></a>
					<a href='?action=".@$_GET['action']."&a=edit&id=".$row[id]."' style=outline:none;float:right><img title='Edit' src=../templates/main/images/edit.png alt='' style=height:27px;margin:-7px;margin-left:12px;></a>
					</td></tr>";	
				}
				echo "</table>";
			}
		}
	}
}
if(@$_GET['action']==2 && $access_articles=="yes") {
	if(@$_GET["a"]=="move_up") {
		$db->query("update ".$db_prefix."articles set orderid=orderid+1 where id = '".$_GET['id']."'");
	}
	elseif(@$_GET["a"]=="move_down") {
		$db->query("update ".$db_prefix."articles set orderid=orderid-1 where id = '".$_GET['id']."'");
	}
	elseif(@$_GET["a"]=="reset_views") {
		if($db->query("update ".$db_prefix."articles set views = 0 where id = '".@$_GET['id']."'"))
			echo "<div class='alert alert-success'>".$text['success'].".</div>";
	}
	if($_POST["new_pageid"]!="") {
		$db->query("update ".$db_prefix."articles set pageid = ".$_POST["new_pageid"]." where id = '".$_POST["id"]."'");
	}
	if(@$_GET["disable_public"]=="true") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."articles set `public` = 'yes' where id = '".@$_GET['id']."'");
	}
	elseif(@$_GET["disable_public"]=="false") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."articles set `public` = 'no' where id = '".@$_GET['id']."'");
	}
	if(@$_GET["disable_comments"]=="true") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."articles set `comments` = 'yes' where id = '".@$_GET['id']."'");
	}
	elseif(@$_GET["disable_comments"]=="false") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."articles set `comments` = 'no' where id = '".@$_GET['id']."'");
	}
	if(@$_GET["disable_page"]=="true") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."articles set `page` = 'yes' where id = '".@$_GET['id']."'");
	}
	elseif(@$_GET["disable_page"]=="false") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."articles set `page` = 'no' where id = '".@$_GET['id']."'");
	}
//	edit
	$_POST['code'] = str_replace("\\'", "'", $_POST['code']);
	$_POST['code'] = str_replace("'", "\\'", $_POST['code']);
	if(@$_GET['dropdown']=="0") {
		$hide=true;
		if($_POST['submit']==$text['save']) {
			($_POST['pageyn']=="yes")?$page2="yes":$page2="no";	
			($_POST['article_comments']=="yes")?$comments="yes":$comments="no";	
			$publish_ts=strtotime($_POST["publish_ts"]);
			(($_POST['article_public']=="yes" && ($publish_ts<time() || $publish_ts=="")) || $publish_ts<time())?$public="yes":$public="no";
			if($_POST['article_public']=="" && $publish_ts=="")$public="no";
			$_POST["code"]=str_replace("&lt;", "<", $_POST["code"]);
			$_POST["code"]=str_replace("&gt;", ">", $_POST["code"]);
			$_POST["code"]=str_replace("&quot;", "\"", $_POST["code"]);
			$_POST["code"]=str_replace("&#039;", "'", $_POST["code"]);
			$_POST["code"]=str_replace("&amp;", "&", $_POST["code"]);
			$_POST["code"]=str_replace("&#039;", "&", $_POST["code"]);
			if($db->query("insert into ".$db_prefix."articles(`pageid`,`title`,`code`,`editor`,`timestamp`,`public`,`publish_ts`,`page`,`comments`) values('".$_POST['pageid']."','".$_POST['title']."','".str_replace("'","\&\#39;",$_POST['code'])."','".$_COOKIE['username']."','".time()."','".$public."','".$publish_ts."','".$page2."','".$comments."')")) {
				echo "<div class='alert alert-success'>". $text['article_add_success'] .".</div>";
				addlog("Beitrag ".$_POST['title']." erstellt.");
				if($_POST["view_next"]=="true" && $_POST["pageidget"]=="" && $db->insert_id!=0)
					echo "<script>window.location.href='../?article=".$db->insert_id."';</script>";
				elseif($_POST["pageidget"]!="")
					echo "<script>window.location.href='../?page=".$_POST['pageid']."';</script>";
			}
			else {
				echo "<div class='alert alert-danger'>". $text['article_add_success_err'] .".</div>";
				addlog("FEHLER: Beitrag ".$_POST['title']." nicht erstellt.");
			}
			$hide=false;
		}
		else {
			echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'" method=post>';
				$result2 = $db->query("select * from ".$db_prefix."pages order by title desc");
				echo $text['page'] ."<br>
				<select name=pageid>";
				echo "<option value=".$row[pageid].">". $text['unchanged'] ."</option>";
				while($row2 = $result2->fetch_assoc()) {
					if($row2[id] != $row[pageid]) {
						if(@$_GET["page"]==$row2[id])
							echo "<option value=".$row2[id]." selected>".$row2[title]."</option>";
						else
							echo "<option value=".$row2[id].">".$row2[title]."</option>";
					}
				}
				echo "</select><br><br>
				". $text['header2'] ."<br>
				<input style=width:400px; name='title' placeholder='". $text['header2'] ."'>
				<textarea name='code' class='htmleditor' placeholder='". $text['text'] ."' style=width:88%;height:250px;></textarea>
				<input type=checkbox name=article_public value=yes checked>". $text['publish'].$text['publish_by_time'] ." <input type=text style='background:none;border:none;border-bottom:1px solid black;padding:0 0;border-radius:0;width:90px;text-align:center' name=publish_ts placeholder='MM/DD/YYYY' class='datepicker'> ).<br>
				";echo(pref("comments")=="yes")?"<input type=checkbox name=article_comments value=yes checked>". $text['allow_comments'] ." <br>":false;echo"
				<input type=checkbox name=view_next value=true ";if(conf("view_next") || @$_GET["view_next"]=="true") echo "checked";echo ">".$text['view_next']."<br>
				<input type=checkbox name=pageyn value=yes>".$text['as']." ".$text['page']."
				<br>
				<br>
				<input name='pageidget' value='".@$_GET["pageid"].@$_GET["page"]."' type='hidden'>
				<input name='submit' value='".$text["save"]."' type='submit'>
				<br>
				<br>
				";	
		}
	}
	
	if(@$_GET['dropdown']=="1") {
		//videoupload
		if(@$_GET['file']!="") {
			echo $file;
		}
		else {
			echo '<form style="font-size:4.5mm;" action="upload.php?action=2&dropdown=1" method=post enctype="multipart/form-data">';
			echo $text['welcomeimg'].'<br>
			<input onchange="this.form.submit()" name="welcome_img" type=file>
			</form>';
		}
	}
	
	
	
	
	
	
	if(@$_GET['a']=="edit") {
		if($_POST['articlesubmit']==$text["save"]) {
			($_POST['article_public']=="yes")?$public="yes":$public="no";			
			($_POST['pageyn']=="yes")?$page="yes":$page="no";			
			($_POST['article_comments']=="yes")?$comments="yes":$comments="no";
			$_POST["code"]=str_replace("&lt;", "<", $_POST["code"]);
			$_POST["code"]=str_replace("&gt;", ">", $_POST["code"]);
			$_POST["code"]=str_replace("&quot;", "\"", $_POST["code"]);
			$_POST["code"]=str_replace("&#039;", "'", $_POST["code"]);
			$_POST["code"]=str_replace("&amp;", "&", $_POST["code"]);
			$_POST["code"]=str_replace("&#039;", "&", $_POST["code"]);
			$publish_ts=strtotime($_POST["publish_ts"]);
			(($_POST['article_public']=="yes" && ($publish_ts<time() || $publish_ts=="")) || $publish_ts<time())?$public="yes":$public="no";
			if($_POST['article_public']=="" && $publish_ts=="")$public="no";
			if($db->query("update ".$db_prefix."articles set `pageid` = '".$_POST['pageid']."', `title` = '".$_POST['title']."', `code` = '".str_replace("'","\&\#39;",$_POST['code'])."', `editor` = '".$_COOKIE['username']."', `timestamp` = '".time()."', `public` = '".$public."', `publish_ts` = '".$publish_ts."', `page` = '".$page."', `comments` = '".$comments."' where id = '".@$_GET['id']."'")) {
				echo "<div class='alert alert-success'>".$text['changes_saved'].".</div>";
				addlog("Beitrag ".$_POST['title']." bearbeitet.");
				if($_POST["view_next"]=="true" && $_POST["pageidget"]=="" && @$_GET['id']!="")
					echo "<script>window.location.href='../?article=".@$_GET['id']."';</script>";
				elseif($_POST["pageidget"]!="")
					echo "<script>window.location.href='../?page=".$_POST['pageid']."';</script>";
			}
			else {
				echo "<div class='alert alert-danger'>".$text['article_edit_err'].".</div>";
				addlog("FEHLER: Beitrag ".$_POST['title']." bearbeitet.");
			}
		}
		else {
			echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&id='.@$_GET['id'].'&a=edit" method="post">';
			echo "<h2>".$text['edit_article']."</h2>";
			$result = $db->query("select * from ".$db_prefix."articles where id = '".@$_GET['id']."'");
			while($row = $result->fetch_assoc()) {
				$result2 = $db->query("select * from ".$db_prefix."pages order by title desc");
				echo "".$text['page']."<br><select name=pageid>";
				echo "<option value=".$row[pageid].">".$text['unchanged']."</option>";
				while($row2 = $result2->fetch_assoc()) {
					if($row2[id] != $row[pageid])
						echo "<option value=".$row2[id].">".$row2[title]."</option>";
				}
				($row['public']=="yes")?$publish_checked="checked":$publish_checked=false;
				($row['comments']=="yes")?$allow_comments_checked="checked":$allow_comments_checked=false;
				echo "</select><br><br>
				".$text['header2']."<br>
				<input style=width:400px; name='title' value='".$row[title]."' placeholder='".$text['header2']."'>
				<textarea name='code' class='htmleditor' placeholder='".$text['text']."' style=width:88%;height:250px;>".$row[code]."</textarea>
				<input type=checkbox name=article_public value=yes ".$publish_checked.">".$text['publish']. $text['publish_by_time'] ." <input type=text style='background:none;border:none;border-bottom:1px solid black;padding:0 0;border-radius:0;width:90px;text-align:center' name=publish_ts placeholder='MM/DD/YYYY' "; echo($row["publish_ts"]=="0")?"":" value='".date("m/d/Y",$row["publish_ts"])."'"; echo" class='datepicker'> ).<br>
				";echo(pref("comments")=="yes")?"<input type=checkbox name=article_comments value=yes ".$allow_comments_checked.">". $text['allow_comments'] ." <br>":false;echo"
				<input type=checkbox name=view_next value=true ";if(conf("view_next") || @$_GET["view_next"]=="true")echo "checked";echo ">".$text['view_next']."<br>
				<input type=checkbox name=pageyn value=yes ";if($row[page]=="yes") echo "checked";echo ">".$text['as']." ".$text['page']."
				<br><br>
				<input name='pageidget' value='".@$_GET["pageid"].@$_GET["page"]."' type='hidden'>
				<input name='articlesubmit' value='".$text["save"]."' type='submit'>
				<br>
				<br>
				";
				}
			echo "</form>";
		}
	}
	if(!$hide) {
		$result = $db->query("select * from ".$db_prefix."articles order by id desc");
		if($result) {
			if(@$_GET['a']=="delete") {
				if($db->query("delete from ".$db_prefix."articles where id = '".@$_GET['id']."'")) {
					$db->query("delete from ".$db_prefix."comments where articleid = '".@$_GET['id']."'");
					echo "<div class='alert alert-success'>".$text['article_removed'].".</div>";
					if(@$_GET["pageid"]!="") echo "<script>window.location.href='../?page=".@$_GET["pageid"]."'</script>";
					addlog("Beitrag '".@$_GET['id']."'  entfernt.");
				}
				else {
					echo "<div class='alert alert-danger'>".$text['article_removed_err'].".</div>";
					addlog("FEHLER: Beitrag '".$_POST['id']."' nicht entfernt.");
				}
			}
			if(@$_GET['a']=="edit" && $_POST['articlesubmit'] != $text["save"]) {
				
			}
			else {
				echo "<table width=100% class='table'>";
				echo "<tr>
				<th style=border:0;width:40px>ID</th>
				<th style=border:0;width:50px>OID</th>
				<th style=border:0;width:100px>".$text['page']."&emsp;</th>
				<th style=border:0;width:100px>&emsp;".$text['title']."</th>
				<th style=border:0;width:100px>".$text['editor']."</th>
				<th style=border:0;width:200px>".$text['date']."</th>
				<th style=border:0;width:20%>".$text['views']."</th>
				<th style=border:0;width:100px>".$text['public']."</th>
				<th style=border:0;width:100px>".$text['comments']."</th>
				<th style=border:0;width:100px>".$text['page']."</th>
				<th style=border:0;width:12%;text-align:right;padding-right:40px>".$text['actions']."</th>
				</tr>";
				$result = $db->query("select * from ".$db_prefix."articles order by id desc");
				while($row = $result->fetch_assoc()) {
					if($row['public']== "yes") {$public="<font color=green>Ja</font>";} else {$public="<font color=#a00>".$text['no']."</font>";}
					if($row['comments']== "yes") {$comments="<font color=green>Ja</font>";} else {$comments="<font color=#a00>".$text['no']."</font>";}
//					doubled
					if($row['public']=="yes") {$public="<a href=?action=2&disable_public=false&id=".$row[id]."><img src=../templates/main/images/switch1.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch0.png')></a>";} elseif($row['publish_ts']!="0") {$public="<a href=?action=2&disable_public=true&id=".$row[id]."><img src=../templates/main/images/switch10.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch1.png')></a>";} else {$public="<a href=?action=2&disable_public=true&id=".$row[id]."><img src=../templates/main/images/switch0.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch1.png')></a>";}
					if($row['comments']=="yes" && pref("comments")=="yes") {$comments="<a href=?action=2&disable_comments=false&id=".$row[id]."><img src=../templates/main/images/switch1.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch0.png')></a>";} else {$comments="<a href=?action=2&disable_comments=true&id=".$row[id]."><img src=../templates/main/images/switch0.png style=";$comments.=(pref("comments")!="yes")?"opacity:.5;height:28px;margin-bottom:-5px>":"height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch1.png')>";$comments.="</a>";}
					if($row['page']=="yes") {$page="<a href=?action=2&disable_page=false&id=".$row[id]."><img src=../templates/main/images/switch1.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch0.png')></a>";} else {$page="<a href=?action=2&disable_page=true&id=".$row[id]."><img src=../templates/main/images/switch0.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch1.png')></a>";}
					echo "<tr>
					<td>".$row[id]."</td>
					<td>".$row[orderid]."</td>
					<td>";
					
					echo '<form action="?action='.@$_GET['action'].'" method="post">';
						$result2 = $db->query("select * from ".$db_prefix."pages order by title desc");
						echo "
						<select name=new_pageid onchange='this.form.submit();' style='margin-bottom:-3px;'>";
						echo "<option value=0>". $text['empty'] ."</option>";
						while($row2 = $result2->fetch_assoc()) {
							echo "<option value='".$row2[id]."' ";echo ($row2[id]==$row[pageid])?"selected":"";echo ">".$row2[title]."</option>";
						}
						echo "</select><input type=hidden name=id value=".$row["id"]."></form>";
						
					echo "</td>
					<td style=padding-left:15px;padding-right:10px>".$row[title]."&nbsp;</td>
					<td>".$row[editor]."&nbsp;</td>
					<td>".date("d.m.Y H:i",$row[timestamp])."</td>
					<td><div style=display:inline-block;min-width:24px;>".$row[views]."</div><a href='?action=".@$_GET['action']."&a=reset_views&id=".$row[id]."'><img src=../templates/main/images/reset.png style=opacity:.5;margin-left:5px;margin-bottom:-2px;></a></td>
					<td>".$public."</td>
					<td>".$comments."</td>
					<td>".$page."</td>
					<td>";
					$i++;
					echo "
					<a href='?action=".@$_GET['action']."&a=move_down&id=".$row[id]."' style=outline:none;><img title='Move down' src=../templates/main/images/move_down.png alt='' style=height:27px;margin:-7px;margin-left:7px;float:right></a>";
					echo "
					<a href='?action=".@$_GET['action']."&a=move_up&id=".$row[id]."' style=outline:none;><img title='Move up' src=../templates/main/images/move_up.png alt='' style=height:27px;margin:-7px;margin-left:7px;margin-right:-2px;float:right></a>";
					echo "
					<a href='../?article=".$row[id]."'  style=outline:none;float:right><img src=../templates/main/images/preview.png title='Preview' alt='' style=height:27px;margin:-7px;margin-left:10px;margin-right:-2px;></a>
					<a href='?action=".@$_GET['action']."&a=delete&id=".$row[id]."' onmouseup=\"alert('".$text['shure_article']."');\"  style=outline:none;float:right><img title='Delete' src=../templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:14px;margin-right:-2px;></a>
					<a href='?action=".@$_GET['action']."&a=edit&id=".$row[id]."' style=outline:none;float:right><img title='Edit' src=../templates/main/images/edit.png alt='' style=height:27px;margin:-7px;margin-left:12px;></a>
					</td></tr>";
				}
				echo "</table>";
			}
		}
	}
}



if(@$_GET['action']==3 && $access_user=="yes") {
	if($_POST["new_groupid"]!="") {
		$db->query("update ".$db_prefix."user set groupid = ".$_POST["new_groupid"]." where id = '".$_POST["id"]."'");
	}
	if(@$_GET["disable"]=="true") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."user set `deactive` = 'yes' where id = '".@$_GET['id']."'");
	}
	elseif(@$_GET["disable"]=="false") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."user set `deactive` = 'no' where id = '".@$_GET['id']."'");
	}
	if(@$_GET["disable_newsletter"]=="true") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."user set `newsletter` = 'yes' where id = '".@$_GET['id']."'");
	}
	elseif(@$_GET["disable_newsletter"]=="false") {
		echo "<div class='alert alert-success'>".$text["success"]."</div>";
		$db->query("update ".$db_prefix."user set `newsletter` = 'no' where id = '".@$_GET['id']."'");
	}
	if(@$_GET['a']=="loginas" && isset($_GET['id'])) {
		$result = $db->query("select * from ".$db_prefix."user where id = '".@$_GET['id']."' limit 1");
		while($row = $result->fetch_assoc()) {
			$_SESSION["tmp_username"]=$row["username"];
			$_SESSION["tmp_groupid"]=$row["groupid"];
			$_SESSION["tmp_userid"]=$row["id"];
			$_SESSION["tmp_password"]=$row["password"];
			echo "<meta http-equiv=refresh content='0,../?do=autologin'>";
		}
	}
	if(@$_GET['dropdown']=="0") {
		$hide=true;
		if($_POST['submit']==$text["save"]) {
			$result2 = $db->query("select * from ".$db_prefix."user where username like '".$_POST['username']."'");
			while($row2 = $result2->fetch_assoc()) {
				echo "".$text['username_already_taken'].". <a href=?action=3&dropdown=0>".$text['back'].".</a>";
				addlog("FEHLER: Username '".$_POST['username']."' bereits vergeben. (Admin)");
				$user_exists=true;
			}
			if(!$user_exists) {
				if($_POST["password"] != $_POST["password_repeat"]) {
					error(1420);return;
				}
				if($db->query("insert into ".$db_prefix."user(`name`,`username`,`groupid`,`password`,`email`,`timestamp`,`deactive`,`md5`) values('".$_POST['name']."','".$_POST['username']."','".$_POST['groupid']."','".md5($_POST['password'])."','".$_POST['email']."','".time()."','".$_POST['deactive']."','".md5(rand(1,99999999999))."')")) {
					echo "<div class='alert alert-success'>".$text['user_created']."</div>";
					addlog("Benutzer ".$_POST['title']." erstellt.");
				}
				else {
					echo "<div class='alert alert-danger'>".$text['error'].".</div>";
					addlog("FEHLER: Benutzer ".$_POST['title']." nicht erstellt.");
				}
				$hide=false;
			}
		}
		else {
			echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'" method=post>';
				$result2 = $db->query("select * from ".$db_prefix."groups order by name desc");
				echo "<h2>".$text["new_user"]."</h2>";
				echo "".$text["group"]."<br><select name=groupid>";
				echo "<option value=0>".$text['no_group']."</option>";
				while($row2 = $result2->fetch_assoc()) {
					echo "<option value=".$row2[id].">".$row2[name]."</option>";
				}
				echo "</select><br><br>
				".$text['name']."<br>
				<input style=width:400px; name='name' placeholder='".$text['name']."'>
				<br><br>
				".$text['username']." *<br>
				<input style=width:400px; name='username' placeholder='".$text['username']."'>
				<br><br>
				".$text['email']." *<br>
				<input style=width:400px; type=email name='email' placeholder='".$text['email']."'>
				<br><br>
				".$text['new_password']." *<br>
				<input style=width:400px; name=password type=password required placeholder='".$text['new_password']."'>
				<br><br>
				<input style=width:400px; name=password_repeat type=password required placeholder='".$text['repeat']."'>
				<br><br>
				<input type=checkbox name=deactive value=yes >".$text['deactive']." <br>
				<br>
				<input name='submit' value='".$text["save"]."' type='submit'>
				";	
		}
	}
	if(@$_GET['a']=="edit") {
		if($_POST['usersubmit']==$text["save"]) {
			if($_POST["password"] != $_POST["password_repeat"]) {
				error(1420);return;
			}
			if($db->query("update ".$db_prefix."user set `name` = '".$_POST['name']."', `email` = '".$_POST['email']."', `groupid` = '".$_POST['groupid']."', `deactive` = '".$_POST['deactive']."' where id = '".@$_GET['id']."'")) {
				echo "<div class='alert alert-success'>".$text['changes_saved']."</div>";
				addlog("Benutzer ".$_POST['title']." bearbeitet.");
			}
			else {
				echo "<div class='alert alert-danger'>".$text['error']."</div>";
				addlog("FEHLER: Benutzer ".$_POST['title']." bearbeitet.");
			}
			if($_POST['password']!="") {
				$db->query("update ".$db_prefix."user set `password` = '".md5($_POST['password'])."' where id = '".@$_GET['id']."'");	
			}
		}
		else {
			echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&id='.@$_GET['id'].'&a=edit" method=post>';
			?>
			<?php
			$result = $db->query("select * from ".$db_prefix."user where id = '".@$_GET['id']."' limit 1");
			while($row = $result->fetch_assoc()) {
				$result2 = $db->query("select * from ".$db_prefix."groups order by name desc");
				echo "<h2>".$text['user2']." ".$row[username]." ".$text['user3']."</h2>";
				echo "".$text['group']."<br><select name=groupid>";
				echo "<option value=".$row[pageid]."> ".$text['unchanged']."</option>";
				while($row2 = $result2->fetch_assoc()) {
					if($row2[id] != $row[groupid]) {
						echo "<option value=".$row2[id].">".$row2[name]."</option>";
					}
				}
				($row["deactive"]=="yes")?$checked="checked":$checked="";
				echo "</select><br><br>
				".$text['name']."<br>
				<input style=width:400px; name='name' value='".$row[name]."'>
				<br><br>
				".$text['username']."<br>
				<input style=width:400px; name='username' value='".$row[username]."' disabled>
				<br><br>
				".$text['email']."<br>
				<input style=width:400px; type=email name='email' value='".$row[email]."'>
				<br><br>
				".$text['new_password']."<br>
				<input style=width:400px; type=password name='password' required placeholder='".$text['new_password']." (".$text['optional'].")'>
				<br><br>
				<input style=width:400px; name=password_repeat type=password required placeholder='".$text['repeat']."'>
				<br><br>
				<input type=checkbox name=deactive value=yes $checked>".$text['deactive']." <br>
				<br>
				<input name='usersubmit' value='".$text["save"]."' type='submit'>
				";	
				}
			?>
			</form>
			<?php
		}
	}
	if(!$hide) {
		$result = $db->query("select * from ".$db_prefix."user order by id desc");
		if($result) {
			if(@$_GET['a']=="delete") {
				if($db->query("delete from ".$db_prefix."user where id = '".@$_GET['id']."'")) {
					$db->query("delete from ".$db_prefix."comments where userid = '".@$_GET['id']."'");
					$result2 = $db->query("select * from `".$db_prefix."user` where id = '".@$_GET['id']."'");
					while($row2=$result2->fetch_object()) {
						$trashfile=$row2->profilepic;
						$username=$row2->username;
					}
					if(conf("delete_trashfiles")) unlink($trashfile);
					else {smkdir(conf("trashpath"));rename($trashfile,conf("trashpath").time().rand(0,99999999999).ending("../../".$trashfile));}

					echo "<div class='alert alert-success'>".$text['user_deleted'].".</div>";
					addlog("Benutzer '".$username."'  entfernt.");
				}
				else {
					echo "<div class='alert alert-danger'>".$text['error'].".</div>";
					addlog("FEHLER: Benutzer '".@$_GET['id']."' nicht entfernt.");
				}
			}
			if(@$_GET['a']=="edit" && $_POST['userubmit'] != $text["save"]) {}
			else {
				echo "<table width=100% class='table'>";
				echo "<tr>
				<th style=border:0;width:3%;>ID</th>
				<th style=border:0; width:3%>".$text['group']."</th>
				<th style=border:0;width:15%>&emsp;".$text['name']."</th>
				<th style=border:0; width:23%>".$text['username']."</th>
				<th style=border:0;width:17%>".$text['email']."</th>
				<th style=border:0; width:19%>".$text['registered']."</th>
				<th style=border:0; width:19%>".$text['last_login']."</th>
				<th style=border:0; width:20%>".$text['newsletter']."</th>
				<th style=border:0; width:20%>".$text['active']."</th>
				<th style=border:0; width=10%>".$text['actions']."</th></tr>";
				$result = $db->query("select * from ".$db_prefix."user order by id desc");
				while($row = $result->fetch_assoc()) {
					if($row[username]!="admin") {
						if($row['deactive']=="no") {$deactive="<a href=?action=3&disable=true&id=".$row[id]."><img src=../templates/main/images/switch1.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch0.png')></a>";} else {$deactive="<a href=?action=3&disable=false&id=".$row[id]."><img src=../templates/main/images/switch0.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch1.png')></a>";}
						if($row['newsletter']=="yes") {$newsletter="<a href=?action=3&disable_newsletter=false&id=".$row[id]."><img src=../templates/main/images/switch1.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch0.png')></a>";} else {$newsletter="<a href=?action=3&disable_newsletter=true&id=".$row[id]."><img src=../templates/main/images/switch0.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch1.png')></a>";}
						if($row[timestamp]=="0") {$registered="---";}else {$registered=date("d.m.Y H:i",$row[timestamp]);}
						if($row[last_login]=="0") {$last_login="---";}else {$last_login=date("d.m.Y H:i",$row[last_login]);}
						echo "<tr><td> ".$row[id]."</td>
						<td> ";/*
						$result2=$db->query("select * from ".$db_prefix."groups where id = '".$row[groupid]."' limit 1");
						while($row2=$result2->fetch_object()) {
							echo $row2->name;
						}*/
						echo '<form action="?action='.@$_GET['action'].'" method="post">';
						$result2 = $db->query("select * from ".$db_prefix."groups");
						echo "
						<select name=new_groupid onchange='this.form.submit();' style='margin-bottom:-4px;'>";
						echo "<option value=0>". $text['empty'] ."</option>";
						while($row2 = $result2->fetch_assoc()) {
							echo "<option value='".$row2[id]."' ";echo ($row2[id]==$row[groupid])?"selected":"";echo ">".$row2[name]."</option>";
						}
						echo "</select><input type=hidden name=id value=".$row["id"]."></form>";
						
						echo "</td>
						<td>".$row[name]."&nbsp;</td>
						<td style=padding-left:15px;padding-right:10px>".$row[username]."&nbsp;</td>
						<td><a href='mailto:".$row[email]."' style='color:#004b77;'>".$row[email]."</a>&nbsp;</td>
						<td>".$registered."&nbsp;</td>
						<td>".$last_login."&nbsp;</td>
						<td>".$newsletter."&nbsp;</td>
						<td>".$deactive."&nbsp;</td>
						<td> 
						<a href='?action=".@$_GET['action']."&a=edit&id=".$row[id]."'><img src=../templates/main/images/edit.png alt='' style=height:27px;margin:-7px;></a>
						<a href='?action=".@$_GET['action']."&a=delete&id=".$row[id]."' onmouseup=\"alert('".$text['del_usr']."?');\"><img src=../templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:10px;></a>";
						if(is_sudo())
						echo "<a href='?action=".@$_GET['action']."&a=loginas&id=".$row[id]."' onmouseup=\"alert('".$text['loginas_warn']."?');\"><img src=../templates/main/images/loginas.png alt='' style=height:27px;margin:-7px;margin-left:15px;></a>";
						echo "</td>
						</tr>";	
					}
				}
				echo "</table>";
			}
		}
	}
}



if(@$_GET['action']==4 && $access_groups=="yes") {
		if(@$_GET["switch"]!="" && @$_GET["pref"]!="") {
			if($db->query("update ".$db_prefix."groups set `edit_".@$_GET["pref"]."` = '".@$_GET["switch"]."' where id = '".@$_GET['id']."'"))
				echo "<div class='alert alert-success'>".$text["success"]."</div>";
		}
		if(@$_GET['dropdown']=="0") {
		$hide=true;
		if($_POST['submit']==$text["save"]) {
			$result2 = $db->query("select * from ".$db_prefix."groups where name = '".$_POST['name']."'");
			while($row2 = $result2->fetch_assoc()) {
				echo "".$text["group_taken"]."<a href=?action=4&dropdown=0>".$text["back"].".</a>";
				addlog("FEHLER: Groupname '".$_POST['name']."' bereits vergeben.");
				$groups_exists=true;
			}
			if(!$groups_exists) {
				if($db->query("insert into ".$db_prefix."groups(`name`,`description`,`edit_articles`,`edit_comments`,`edit_user`,`edit_pages`,`edit_groups`,`edit_newsletter`,`edit_prefs`,`edit_forum`,`edit_shop`) values('".$_POST['name']."','".$_POST['description']."','".$_POST['edit_articles']."','".$_POST['edit_comments']."','".$_POST['edit_user']."','".$_POST['edit_pages']."','".$_POST['edit_groups']."','".$_POST['edit_newsletter']."','".$_POST['edit_prefs']."','".$_POST['edit_forum']."','".$_POST['edit_shop']."')")) {
					echo "<div class='alert alert-success'>".$text["group_created"].".</div>";
					addlog("Gruppe ".$_POST['name']." erstellt.");
				}
				else {
					echo "<div class='alert alert-danger'>".$text["error"].".</div>";
					addlog("FEHLER: Gruppe ".$_POST['name']." nicht erstellt.");
				}
				$hide=false;
			}
		}
		else {
			echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'" method=post>';
				$result2 = $db->query("select * from ".$db_prefix."groups order by name desc");
				
				echo "<h2>".$text["group_new"]."</h2>
				".$text["name"]."<br>
				<input style=width:400px; name='name' id='name' placeholder='".$text["name"]."'>
				<br><br>
				".$text["description"]."&nbsp;<br>
				<input style=width:400px; name='description' id='description' placeholder='".$text["description"]."'>
				<br><br>
				
				<legend>".$text["group_accesses2"]." ?</legend>
				<br>
				".$text["group_accesses3"]."
				
				<select name=edit_articles>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				".$text["group_accesses4"]."
				
				<select name=edit_comments>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				
				".$text["group_accesses5"]."
				
				<select name=edit_user>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				
				".$text["group_accesses6"]."
				
				<select name=edit_pages>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				
				".$text["group_accesses7"]."
				
				<select name=edit_groups>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				
				".$text["group_accesses8"]."
				
				<select name=edit_newsletter>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				
				".$text["group_accesses9"]."
				
				<select name=edit_prefs>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				
				".$text["group_accesses10"]."
				
				<select name=edit_forum>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				
				".$text["group_accesses11"]."
				
				<select name=edit_shop>
				<option value=yes>".$text["yes"]."</option>
				<option value=no selected>".$text["no"]."</option>
				</select><br><br>
				
				<input name='submit' value='".$text["save"]."' type='submit'>
				";	
		}
	}
	if(@$_GET['a']=="edit") {
		if($_POST['groupsubmit']==$text["save"]) {
			if($db->query("update ".$db_prefix."groups set `name` = '".$_POST['name']."', `description` = '".$_POST['description']."',`edit_articles` = '".$_POST['edit_articles']."',`edit_comments` = '".$_POST['edit_comments']."',`edit_user` = '".$_POST['edit_user']."',`edit_pages` = '".$_POST['edit_pages']."',`edit_groups` = '".$_POST['edit_groups']."',`edit_newsletter` = '".$_POST['edit_newsletter']."',`edit_prefs` = '".$_POST['edit_prefs']."',`edit_forum` = '".$_POST['edit_forum']."',`edit_shop` = '".$_POST['edit_shop']."' where id = '".@$_GET['id']."'")) {
				echo "<div class='alert alert-success'>".$text['changes_saved']."</div>";
				addlog("Gruppe ".$_POST['name']." bearbeitet.");
			}
			else {
				echo "<div class='alert alert-danger'>".$text['error']."</div>";
				addlog("FEHLER: Gruppe ".$_POST['name']. " nicht bearbeitet.");
			}
		}
		else {
			echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&id='.@$_GET['id'].'&a=edit" method=post>';
			?>
			<?php
			$result = $db->query("select * from ".$db_prefix."groups where id = '".@$_GET['id']."'");
			while($row = $result->fetch_assoc()) {
				
				if($row[edit_articles]=="yes") {$edit_articles_yes="selected";} else {$edit_articles_no="selected";}
				if($row[edit_comments]=="yes") {$edit_comments_yes="selected";} else {$edit_comments_no="selected";}
				if($row[edit_user]=="yes") {$edit_user_yes="selected";} else {$edit_user_no="selected";}
				if($row[edit_pages]=="yes") {$edit_pages_yes="selected";} else {$edit_pages_no="selected";}
				if($row[edit_groups]=="yes") {$edit_groups_yes="selected";} else {$edit_groups_no="selected";}
				if($row[edit_newsletter]=="yes") {$edit_newsletter_yes="selected";} else {$edit_newsletter_no="selected";}
				if($row[edit_prefs]=="yes") {$edit_prefs_yes="selected";} else {$edit_prefs_no="selected";}
				if($row[edit_forum]=="yes") {$edit_forum_yes="selected";} else {$edit_forum_no="selected";}
				if($row[edit_shop]=="yes") {$edit_shop_yes="selected";} else {$edit_shop_no="selected";}
				
				echo "<h2>".$text["edit_group"]."</h2>
				".$text["name"]."<br>
				<input style=width:400px; value='".$row[name]."' name='name' placeholder='".$text["name"]."'>
				<br><br>
				".$text["description"]."<br>
				<input style=width:400px; value='".$row[description]."' name='description' placeholder='".$text["description"]."'>
				<br><br>
				
				<legend>".$text["group_accesses2"]." ?</legend>
				<br>
				".$text["group_accesses3"]."
				
				<select name=edit_articles>
				<option value=yes $edit_articles_yes>".$text['yes']."</option>
				<option value=no $edit_articles_no>".$text['no']."</option>
				</select><br><br>
				
				".$text["group_accesses4"]."
				
				<select name=edit_comments>
				<option value=yes $edit_comments_yes>".$text['yes']."</option>
				<option value=no  $edit_comments_no>".$text['no']."</option>
				</select><br><br>
				
				
				".$text["group_accesses5"]."
				
				<select name=edit_user>
				<option value=yes $edit_user_yes>".$text['yes']."</option>
				<option value=no  $edit_user_no>".$text['no']."</option>
				</select><br><br>
				
				
				".$text["group_accesses6"]."
				
				<select name=edit_pages>
				<option value=yes $edit_pages_yes>".$text['yes']."</option>
				<option value=no  $edit_pages_no>".$text['no']."</option>
				</select><br><br>
				
				
				".$text["group_accesses7"]."
				
				<select name=edit_groups>
				<option value=yes $edit_groups_yes>".$text['yes']."</option>
				<option value=no  $edit_groups_no>".$text['no']."</option>
				</select><br><br>
				
				
				".$text["group_accesses8"]."
				
				<select name=edit_newsletter>
				<option value=yes $edit_newsletter_yes>".$text['yes']."</option>
				<option value=no  $edit_newsletter_no>".$text['no']."</option>
				</select><br><br>
				
				
				".$text["group_accesses9"]."
				
				<select name=edit_prefs>
				<option value=yes $edit_prefs_yes>".$text['yes']."</option>
				<option value=no  $edit_prefs_no>".$text['no']."</option>
				</select><br><br>
				
				
				".$text["group_accesses10"]."
				
				<select name=edit_forum>
				<option value=yes $edit_forum_yes>".$text["yes"]."</option>
				<option value=no $edit_forum_no>".$text["no"]."</option>
				</select><br><br>
				
				
				".$text["group_accesses11"]."
				
				<select name=edit_shop>
				<option value=yes $edit_shop_yes>".$text["yes"]."</option>
				<option value=no $edit_shop_no>".$text["no"]."</option>
				</select><br><br>
				
				
				<input name='groupsubmit' value='".$text["save"]."' type='submit'>
				";	
				}
			?>
			</form>
			<?php
		}
	}
	if(!$hide) {
		$result = $db->query("select * from ".$db_prefix."groups order by id desc");
		if($result) {
			if(@$_GET['a']=="delete") {
				if($db->query("delete from ".$db_prefix."groups where id = '".@$_GET['id']."'")) {
					echo "<div class='alert alert-success'>
				".$text["group_del"].".</div>";
				addlog("Gruppe '".@$_GET['id']."'  entfernt.");
				}
				else {
					echo "<div class='alert alert-danger'>".$text["group_del_err"].".</div>";
				addlog("FEHLER: Gruppe '".$_POST['id']."' nicht entfernt.");
				}
			}
			if(@$_GET['a']=="edit" && $_POST['groupsubmit'] != $text["save"]) {
				
			}
			else {
				echo "<table width=100% class='table'>";
				echo "<tr>
				<th style='border:0;width:40px;'>ID</th>
				<th style='border:0;width:120px;'>".$text["name"]."</th>
				<th style='border:0;width:200px;'>".$text["description"]."</th>";
				for($i=1;$i <= 9;$i++) {
					echo "<th style='border:0;width:80px; !important'>".$text["group_prefs".$i]."</th>";
				}
				echo "<th style='border:0;width:100px;'>".$text["actions"]."</th></tr>";
				$result = $db->query("select * from ".$db_prefix."groups order by id desc");
				while($row = $result->fetch_assoc()) {
					if($row[username]!="admin") {
						echo "<tr>
						<td> ".$row[id]."&nbsp;</td>
						<td> ".$row[name]."&nbsp;</td>
						<td> ".$row[description]."&nbsp;</td>";
						$edit_array=array("articles","comments","user","pages","groups","newsletter","prefs","forum","shop");
						foreach($edit_array as $edit) {
							$switch=($row["edit_".$edit]=="no")?array("yes","1","0"):array("no","0","1");
							if($edit=="groups"||$edit=="user"){$switch=($row["edit_".$edit]=="no")?array("yes","3","0"):array("no","0","3");}
							echo "
							<td>
								<a href=?action=4&switch=".$switch[0]."&pref=".$edit."&id=".$row[id].">
								<img src=../templates/main/images/switch".$switch[1].".png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch".$switch[2].".png')>
								</a>
							</td>";
						}
						echo "
						<td> 
						<a href='?action=".@$_GET['action']."&a=edit&id=".$row[id]."'><img src=../templates/main/images/edit.png alt='' style=height:27px;margin:-7px;></a>
						<a href='?action=".@$_GET['action']."&a=delete&id=".$row[id]."' onmouseup=\"alert('".$text['shure_group']."?');\"><img src=../templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:10px;></a>
						</td></tr>";	
					}
				}
				echo "</table>";
			}
		}
	}
}
if(@$_GET['action']==5 && $access_comments=="yes") {
	if(@$_GET['a']=="delete") {
		if($db->query("delete from ".$db_prefix."comments where id = '".@$_GET['id']."'")) {
			echo "<div class='alert alert-success'>".$text["comment_del"].".</div>";
		addlog("Kommentar '".@$_GET['id']."'  entfernt.");
		}
		else {
			echo "<div class='alert alert-danger'>".$text["comment_del_err"]."</div>";
		addlog("FEHLER: Kommentar '".$_POST['id']."' nicht entfernt.");
		}
	}
	if(@$_GET["article"]!="") $_POST["article"]=@$_GET["article"];
	$result2 = $db->query("select * from ".$db_prefix."articles where comments='yes' order by timestamp desc limit 5");
	if($result2) {
		echo "<form action=?action=5 method=post><select name=article style=width:100% onchange='this.form.submit();'>";
		echo "<option value='' >".$text["all"]."</option>";
		while($row2 = $result2->fetch_assoc()) {
			echo "<option value='".$row2[id]."' ";if($_POST["article"]==$row2[id]) echo "selected"; echo ">".$row2[title]."</option>";
		}
		echo "</select></form><br>";
	}
	if($_POST["article"]!="") $article="where `articleid` = '".$_POST["article"]."'";	
	$result = $db->query("select * from ".$db_prefix."comments ".$article." order by timestamp desc limit 100");
	if($result) {
		while($row = $result->fetch_assoc()) {
			$users = $db->query("select * from ".$db_prefix."user where id = '".$row[userid]."' limit 1");
			if($users) {
				while($user = mysql_fetch_array($users)) {
					$username = $user[username];
				}
			}
			echo "<div class=comment onmouseover='this.style.backgroundColor=\"#ddd\"' onmouseout='this.style.backgroundColor=\"#eee\"'>".ucfirst($username)."<br>".ucfirst($row[msg])."<a href=?action=5&article=".$_POST["article"]."&a=delete&id=".$row[id]."><div class=deleteicon><img src=../templates/main/images/delete_comment.png height=100%></div></a></div>";
		$comments = true;	
		}
	}
	if(!$comments) {
		echo "<br><center><i style=opacity:.4;>".$text["no_comments_yet"].".</i><br><br>";	
	}
}
if(@$_GET['action']==6 && $access_newsletter=="yes") {
	if(isset($_POST['msg'])) {
		$result = $db->query("select * from ".$db_prefix."user");
		if($result) {
			file_put_contents("../log/system.log","\n".@file_get_contents("../log/system.log").@file_get_contents("../log/newsletter.log"));
			unlink("../log/newsletter.log");
			while($row = $result->fetch_assoc()) {
				if($row['newsletter']!="no") {
					$title="From: Newsletter@".pref("website_title");
					$unsubscribe = "\n\n\n------------------------ \nWenn Sie diesen Newsletter nicht mehr erhalten wollen, klicken Sie auf diesen Link:\nhttp://".$_SERVER["SERVER_NAME"]."/index.php?go=unsubscribe&md5=".$row['md5']."&".$row[name];
					if(mail($row['email'], $_POST['subject'], $_POST['msg'].$unsubscribe,$title)) {
						file_put_contents("../log/newsletter.log",@file_get_contents("../log/newsletter.log")."\n".$text['newsletter1']." (".$row['name'].")");
					}
					else {
						file_put_contents("../log/newsletter.log",@file_get_contents("../log/newsletter.log")."\n".$text['newsletter2']." (".$row['name'].")");
					}
				}
				else {
					file_put_contents("../log/newsletter.log",@file_get_contents("../log/newsletter.log")."\n".$text['newsletter3']." (".$row['name'].")");
				}
			}
			echo "<br><h2>".$text["success"]."</h2><a href=../log/newsletter.log>&gt;&gt; ".$text["newsletter_log"]."</a>";
		}
	}
	else {
		echo "<h2>".$text['newsletter']."</h2>
		<form action=?action=6&".$text['newsletter']." method=post>
		<input name=subject placeholder='".$text['subject']."'  style=width:600px;><br>
		<textarea name=msg placeholder='".$text['newsletter']." ".$text['text']."' style='background:hsla(0,0%,100%,.5);border:1px solid white;margin:5px 0px;width:600px;height:200px;'></textarea><br>
		<input type=submit value='".$text['send_nl']."'>
		</form>
		";
	}
}
if(@$_GET['action']==7 && ($access_forum=="yes" | $access_shop=="yes")) {
	if(@$_GET["dropdown"]==2) {
		if(isset($_POST["submit"])) {
			$file = "files/".time()."-".$_FILES['welcome_img']['name'];
			if(move_uploaded_file($_FILES['image']['tmp_name'], "../".$file)) {echo "";}
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("INSERT INTO `".$db_prefix."shop_items` (`id`, `title`, `description`, `image`, `price`, `views`, `timestamp`, `categorieid`) VALUES (NULL, '".$_POST["title"]."', '".$_POST["description"]."', '".$file."', '".$_POST["price"]."', '', CURRENT_TIMESTAMP, '".$_POST["categorieid"]."')");
		}
		echo '<form enctype="multipart/form-data" style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'&id='.@$_GET['id'].'&a=add" method=post>';
		echo "<h2>".$text["new_item"]."</h2>";
		echo $text["name"]."<br>";
		echo "
		<input name='title'>
		<br><br>
		". $text['description'] ."<br>
		<input name='description'>
		<br><br>	
		". $text['image'] ."<br>
		<input name='image' type=file>
		<br><br>	
		". $text['price'] ."<br>
		<input name='price' size=1> ".pref("currency")."
		<br><br>	
		". $text['categorie'] ."<br>
		<select name='categorieid'><option>Keine</option>";
			$result=$db->query("select * from `".$db_prefix."shop_categories` ");
			while($row = $result->fetch_assoc()) {
				echo "<option value=".$row[id].">".$row[name]."</option>";
			}
		echo "</select><br><br>	
		<input name='submit' value='".$text[save]."' type='submit'>
		<br>
		</form>
		";	
	}
	if(@$_GET["dropdown"]==24) {
		if(isset($_POST["submit"])) {
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("INSERT INTO `".$db_prefix."shop_categories` (`name`) VALUES ('".$_POST["name"]."')");
		}
		echo '<form enctype="multipart/form-data" style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'&id='.@$_GET['id'].'&a=add" method=post>';
		echo "<h2>".$text["new_categorie"]."</h2>
		<input name='name' placeholder='".$text["name"]."'>
		<br><br>
		<input name='submit' value='".$text[save]."' type='submit'>
		<br>
		</form>
		";
	}
	if(@$_GET["dropdown"]==21 && isset($_GET["id"]) && @$_GET["a"]=="save") {
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("update `".$db_prefix."shop_items` set `title`='".$_POST["title"]."', `description`='".$_POST["description"]."', `price`='".$_POST["price"]."', `categorieid`='".$_POST["categorieid"]."' where id  = '".@$_GET["id"]."'");
			$showtable=1;
	}
	if(@$_GET["dropdown"]==21 && isset($_GET["id"]) && @$_GET["a"]=="delete") {
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("delete from `".$db_prefix."shop_items` where id  = '".@$_GET["id"]."'");
			$showtable=1;
	}
	if(@$_GET["dropdown"]==11 && isset($_GET["id"]) && @$_GET["a"]=="delete") {
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("delete from `".$db_prefix."forum_questions` where id  = '".@$_GET["id"]."'");
			$showtable3=1;
	}
	if(@$_GET["dropdown"]==11 && isset($_GET["id"]) && @$_GET["a"]=="save") {
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("update `".$db_prefix."forum_questions` set `question`='".$_POST["question"]."' where id  = '".@$_GET["id"]."'");
			$showtable3=1;
	}
	if(@$_GET["dropdown"]==12 && @$_GET["a"]=="add" && isset($_POST["submit"])) {
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("insert into `".$db_prefix."forum_questions` (`question`,`userid`) values('".$_POST["question"]."','".$_COOKIE["userid"]."')");
			$showtable3=1;
	}
	if(@$_GET["dropdown"]==12 && @$_GET["a"] != "delete" && $_POST["submit"]=="") {
		echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'&id='.@$_GET['id'].'&a=add" method=post>';
		echo "<h2>".$text["question"]."</h2>";
		echo "<input name='question' placeholder='".$text["question"]."'>";
		echo "
		<br><br>
		<input name='submit' value='".$text[save]."' type='submit'>
		<br>
		</form>";	
	}
	if(@$_GET["dropdown"]==11 && isset($_GET['id']) && @$_GET["a"]=="edit") {
		echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'&id='.@$_GET['id'].'&a=save" method=post>';
		echo "<h2>".$text["question"]."</h2>";
		$result2 = $db->query("select * from ".$db_prefix."forum_questions where id = '".@$_GET["id"]."'");
		while($row2 = $result2->fetch_assoc()) {
			echo "<input name='question' value='".$row2['question']."' placeholder='".$text["question"]."'>";
		}
		echo "
		<br><br>
		<input name='submit' value='".$text[save]."' type='submit'>
		<br>
		</form>";	
	}
	if((@$_GET["dropdown"]==11 && @$_GET['id']=="")|$showtable3) {
		echo "<table width=100% class='table'>";
		echo "<tr>
		<th style=border:0;width:3%;>ID</th>
		<th style=border:0; width=60%>".$text["question"]."</th>
		<th style=border:0; width=10%>".$text["user"]."</th>
		<th style=border:0; width=150>".$text["created"]."</th>
		<th style=border:0; width=1%>".$text["actions"]."</th></tr>";
		$result = $db->query("select * from ".$db_prefix."forum_questions order by timestamp desc");
		while($row = $result->fetch_assoc()) {
			if($row[username]!="admin") {
				echo "<tr>
				<td> ".$row[id]."</td>
				<td> ".$row[question]."</td><td>";
				$result2 = $db->query("select * from ".$db_prefix."user where id = '".$row[userid]."'");
				while($row2 = $result2->fetch_assoc()) {
					echo $row2[username];	
				}
				echo "</td>
				<td> ".$row[timestamp]."</td>
				<td>
				<a href='?action=".@$_GET['action']."&dropdown=".@$_GET['dropdown']."&a=edit&id=".$row[id]."'><img src=../templates/main/images/edit.png alt='' style=height:27px;margin:-7px;></a>
				<a href='?action=".@$_GET['action']."&dropdown=".@$_GET['dropdown']."&a=delete&id=".$row[id]."' onmouseup=\"alert('".$text['shure']." ?');\"><img src=../templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:10px;></a>
				</td></tr>";	
			}
		}
		echo "</table>";
	}
	if((@$_GET["dropdown"]==21 && @$_GET['id']=="")|$showtable) {
		echo "<table width=100% class='table'>";
		echo "<tr>
		<th style=border:0;width:3%;>ID</th>
		<th style=border:0; width=30%>".$text["name"]."</th>
		<th style=border:0; width=40%>".$text["description"]."</th>
		<th style=border:0;width:150px;>".$text["price"]."</th>
		<th style=border:0;width:190px;>".$text["categorie"]."</th>
		<th style=border:0; width=1%>".$text["actions"]."</th></tr>";
		$result = $db->query("select * from ".$db_prefix."shop_items order by id desc");
		while($row = $result->fetch_assoc()) {
			if($row[username]!="admin") {
				echo "<tr>
				<td> ".$row[id]."&nbsp;</td>
				<td> ".$row[title]."&nbsp;</td>
				<td> ".$row[description]."&nbsp;</td>
				<td> ".$row[price]."&nbsp;".pref("currency")."</td>
				<td> ";
				$result2 = $db->query("select * from ".$db_prefix."shop_categories where id = '".$row[categorieid]."'");
				while($row2 = $result2->fetch_assoc()) {
					echo $row2[name];	
				}
				echo "&nbsp;</td>
				<td> 
				<a href='?action=".@$_GET['action']."&dropdown=".@$_GET['dropdown']."&a=edit&id=".$row[id]."'><img src=../templates/main/images/edit.png alt='' style=height:27px;margin:-7px;></a>
				<a href='?action=".@$_GET['action']."&dropdown=".@$_GET['dropdown']."&a=delete&id=".$row[id]."' onmouseup=\"alert('".$text['shure']." ?');\"><img src=../templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:10px;></a>
				</td></tr>";	
			}
		}
		echo "</table>";
	}
	if(@$_GET["dropdown"]==23 && @$_GET["a"]=="delete") {
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("delete from `".$db_prefix."shop_categories` where id  = '".@$_GET["id"]."'");
			$showtable2=1;
	}
	if((@$_GET["dropdown"]==23 && @$_GET['id']=="")|$showtable2) {
		echo "<table width=100% class='table'>";
		echo "<tr>
		<th style=border:0;width:3%;>ID</th>
		<th style=border:0; width=99%>".$text["name"]."</th>
		<th style=border:0; width=1%>".$text["actions"]."</th></tr>";
		$result = $db->query("select * from ".$db_prefix."shop_categories order by id desc");
		while($row = $result->fetch_assoc()) {
			if($row[username]!="admin") {
				echo "<tr>
				<td> ".$row[id]."</td>
				<td> ".$row[name]."&nbsp;</td>
				<td>
				<a href='?action=".@$_GET['action']."&dropdown=".@$_GET['dropdown']."&a=edit&id=".$row[id]."'><img src=../templates/main/images/edit.png alt='' style=height:27px;margin:-7px;></a>
				<a href='?action=".@$_GET['action']."&dropdown=".@$_GET['dropdown']."&a=delete&id=".$row[id]."' onmouseup=\"alert('".$text['shure']." ?');\"><img src=../templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:10px;></a>
				</td></tr>";	
			}
		}
		echo "</table>";
	}
	if(@$_GET["dropdown"]==23 && @$_GET["a"]=="edit") {
		if(isset($_POST["submit"])) {
			echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
			$db->query("update `".$db_prefix."shop_categories` set `name`='".$_POST["name"]."' where id  = '".@$_GET["id"]."'");
		}
		echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'&id='.@$_GET['id'].'&a='.@$_GET["a"].'" method=post>';
		echo "<h2>".$text["categorie"]."</h2>";
		$result2 = $db->query("select * from ".$db_prefix."shop_categories where id = '".@$_GET["id"]."'");
		while($row2 = $result2->fetch_assoc()) {
			echo "<input name='name' value='".$row2['name']."' placeholder='".$text["name"]."'>";
		}
		echo "
		<br><br>
		<input name='submit' value='".$text[save]."' type='submit'>
		<br>
		</form>";	
	}
	elseif(@$_GET["dropdown"]==21 && isset($_GET["id"])) {
		$result = $db->query("select * from ".$db_prefix."shop_items where id = '".@$_GET["id"]."'");
		while($row = $result->fetch_assoc()) {
			echo '<form enctype="multipart/form-data" style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'&id='.@$_GET['id'].'&a=save" method=post>';
			echo "<h2>".$text["new_item"]."</h2>";
			echo $text["name"]."<br>";
			echo "
			<input name='title' value='".$row[title]."'>
			<br><br>
			". $text['description'] ."<br>
			<input name='description' value='".$row[description]."'>
			<br><br>	
			". $text['price'] ."<br>
			<input name='price' value='".$row[price]."' size=1> ".pref("currency")."
			<br><br>	
			". $text['categorie'] ."<br>
			<select name='categorieid'><option>Keine</option>";
				$result2=$db->query("select * from `".$db_prefix."shop_categories` ");
				while($row2 = $result2->fetch_assoc()) {
					if($row[categorieid]==$row2[id]) {
						echo "<option value=".$row2[id]." selected>".$row2[name]."</option>";
					}
					else {
						echo "<option value=".$row2[id].">".$row2[name]."</option>";
					}
				}
			echo "</select><br><br>	
			<input name='submit' value='".$text[save]."' type='submit'>
			<br>
			</form>
			";	
		}
	}
}
if(@$_GET['action']==8 && is_sudo()) {
	if(@$_GET["dropdown"]=="") {
	$SERVER=$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
	echo "<style>#admincontainer{padding:0 !Important}</style><iframe width=100% frameborder=0 style=height:99.6%; src='//domiscms.de/addons.php?v=$v&l=".$l."&feature=incomis&referer=".$SERVER."&bl'><a href=//domiscms.de/addons.php?v=$v&l=".$l.">hier</a></iframe>";
	}
	elseif(@$_GET["dropdown"]=="0") {	
		@$_GET["file"]="../addons/".@$_GET["file"];
		if(@$_GET["disable"]=="true") {
			echo "<div class='alert alert-success'>".$text["success"]."</div>";
			rename(@$_GET["file"],@$_GET["file"].".disabled");
		}
		elseif(@$_GET["disable"]=="false") {
			echo "<div class='alert alert-success'>".$text["success"]."</div>";
			rename(@$_GET["file"],str_replace(".disabled","",@$_GET["file"]));
		}
		if(@$_GET['a']=="delete") {
			if(unlink("../addons/".str_replace("/","",@$_GET['id']))) {
				addlog("Addon '".@$_GET['id']."'  entfernt.");
			}
			else {
				addlog("FEHLER: Addon '".@$_GET["id"]."' nicht entfernt.");
			}
		}
		echo "<table width=100% class='table'>";
		echo "<tr>
		<th style=border:0;width:10%>".$text["name"]."</th>
		<th style=border:0;width:13%>".$text["categorie"]."</th>
		<th style=border:0;width:11%>".$text["screen"]."</th>
		<th style=border:0;width:90%>".$text["disabled"]."</th>
		<th style=border:0;width:100px>".$text["actions"]."</th></tr>";
		foreach(glob("../addons/*.php*") as $file) {
			$tmp=explode("../addons/",$file);$file=$tmp[1];
			$origin=$file;
			$tmp=explode(".",$tmp[1]);
			echo "<tr>
			<td> ".ucfirst($tmp[0])."&nbsp;</td>
			<td> ";if($tmp[1]=="mainpage")echo $text["mainpage"];elseif($tmp[1]=="admin")echo $text["admin"];echo "</td>
			<td> ";echo($tmp[2]=="mobile")?$text["mobile"]:$text["desktop"];echo "</td>
			<td> ";echo(strstr($file,".php.disabled"))?"<span style=color:#a00>".$text["yes"]."</span>
			<a style=float:right;margin-right:20px; href=?action=8&dropdown=0&disable=false&file=$file><img src=../templates/main/images/switch0.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch1.png')></a>
			":"<span style=color:green>".$text["no"]."</span>
			<a style=float:right;margin-right:20px; href=?action=8&dropdown=0&disable=true&file=$file><img src=../templates/main/images/switch1.png style=height:28px;margin-bottom:-5px onmousedown=$(this).attr('src','../templates/main/images/switch0.png')></a>
			";echo "</td>
			<td>
			<a href='?action=200&parent_action=8&file=../addons/".$origin."&return=?action=8(and)dropdown=0'><img src=../templates/main/images/edit.png alt='' style=height:27px;margin:-7px;></a>
			<a href='?action=".@$_GET['action']."&dropdown=".@$_GET['dropdown']."&a=delete&id=".$origin."' onmouseup=\"alert('".$text['shure']."');\"><img src=../templates/main/images/delete.png alt='' style=height:27px;margin:-7px;margin-left:10px;></a>
			</td></tr>";
		}
		echo "</table>";
	}
	elseif(@$_GET["dropdown"]=="1") {
		if(isset($_POST["submit"])) {
			if(!strstr(@file_get_contents("../etc/signatures.list"),md5($contents)) && !strstr(@file_get_contents("http://domiscms.de/addons/signatures.list"),md5($contents))) {
				alert("No signature! Installation aborted! If you want to install though please change your main.conf and set require_signature from true to false. Normaly you would be able now, to choose between two options, but because of the stupidness of the device this programm was coded on this option is not avaiable. We're not sorry! Add this ".md5($contents)."  as a new line to your etc/sources.list, and repeat the installation.");
				echo "<meta http-equiv=refresh content='0,?action=8&dropdown=0'>";
			}
			else {
				if(!is_dir("../addons")) mkdir("../addons");
				$zip = new ZipArchive;
				$uniqid="comis_export_".time().uniqid();
				if(strstr($_FILES['file']['name'],".zip")) {
					move_uploaded_file($_FILES['file']['tmp_name'], "../files/tmp/".$uniqid.'.zip');
					if ($zip->open("../files/tmp/".$uniqid.'.zip') === TRUE) {
						$zip->extractTo('../addons/');
						$zip->close();
					}
					else echo "UPLOAD FAILED";
					unlink("../files/tmp/".$uniqid.'.zip');
				}
				elseif(strstr($_FILES['file']['name'],".php")) {
					move_uploaded_file($_FILES['file']['tmp_name'], "../addons/".$_FILES['file']['name']);
				}
				echo "<h2>".$text["addon_installed"]."</h2>";
			}
		}
		else {
			echo '<form enctype="multipart/form-data" action="?action=8&dropdown=1" method="post">';
			echo "<h2>".$text["please_select_file"].":</h2>
			<input name='file' placeholder='".$text["file"]."' type=file required>
			<input name='submit' value='".$text["upload"]."' type='submit'>
			<br><tt style=margin:8px;display:block>".$text["allowed_types"]." php, zip</tt>
			</form>
			";
		}
	}
}
if($_GET['action']==9 && $access_prefs=="yes") {
	if(isset($_POST["website_template"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['website_template']."' where name = 'website_template'");
		addlog("ADMIN: Webseiten Design wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["admin_template"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['admin_template']."' where name = 'admin_template'");
		addlog("ADMIN: Admin Design wurde ge&auml;ndert.");
	$reload_site=true;$success=true;}
	if(isset($_GET["welcome_img"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_GET['welcome_img']."' where name = 'welcome_img'");
		addlog("ADMIN: Willkommensbild wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["welcome_img_yn"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['welcome_img_yn']."' where name = 'welcome_img_yn'");
		addlog("ADMIN: Willkommensbild wurde ge&auml;ndert.");
	$reload_site=true;$success=true;}
	if(isset($_POST["headpicture_yn"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['headpicture_yn']."' where name = 'headpicture_yn'");
		addlog("ADMIN: Webseitenbanner wurde ge&auml;ndert.");
	$reload_site=true;$success=true;}
	if(isset($_GET["headpicture"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_GET['headpicture']."' where name = 'headpicture'");
		addlog("ADMIN: Willkommensbild wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["background_yn"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['background_yn']."' where name = 'background_yn'");
		addlog("ADMIN: Webseitenbanner wurde ge&auml;ndert.");
	$reload_site=true;$success=true;}
	if(isset($_GET["background"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_GET['background']."' where name = 'background'");
		addlog("ADMIN: Willkommensbild wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_GET["bg_img"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_GET['bg_img']."' where name = 'bg_img'");
		addlog("ADMIN: Hintergrundbild wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["welcome_msg"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['welcome_msg']."' where name = 'welcome_msg'");
		addlog("ADMIN: Willkommensnachricht wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["website_title"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['website_title']."' where name = 'website_title'");
		addlog("ADMIN: Webseitentitel wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["website_description"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['website_description']."' where name = 'website_description'");
		addlog("ADMIN: Webseitenbeschreibung wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["user_email"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['user_email']."' where name = 'user_email'");
		addlog("ADMIN: Admin Email wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["user_name"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['user_name']."' where name = 'user_name'");
		addlog("ADMIN: Admin Name wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["user_password"])) {
		$db->query("update ".$db_prefix."user set password = '".md5($_POST['user_password'])."' where username = 'admin'");
		addlog("ADMIN: Admin Passwort wurde ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["language"])) {
		if($_POST["language"]!="de" && $_POST["language"]!="en")
			alert($text["third_translation_warn"]);
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['language']."' where name = 'language'");
		addlog("ADMIN: Sprache gewechselt.");
	$reload_site=true;$success=true;}
	if(isset($_POST["width_container"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['width_container']."' where name = 'width_container'");
		addlog("ADMIN: Breite der Webseite ge&auml;ndert.");
	$success=true;}
	if(isset($_POST["home_article"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['home_article']."' where name = 'home_article'");
		addlog("ADMIN: Home Seite der Webseite bearbeitet.");
	$success=true;}
	if(isset($_POST["search"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['search']."' where name = 'search'");
		addlog("ADMIN: Sucheinstellungen gewechselt.");
	$success=true;}
	if(isset($_POST["animations"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['animations']."' where name = 'animations'");
		addlog("ADMIN: Animationseinstellung gewechselt.");
	$success=true;}
	if(isset($_POST["aside"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['aside']."' where name = 'aside'");
		addlog("ADMIN: Einstellungen f&uuml;r Artikelvorschl&auml;ge ge&auml;ndert.");	
	$success=true;}
	if(isset($_POST["website_title"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['website_title']."' where name = 'website_title'");
		addlog("ADMIN: Einstellungen f&uuml;r Webseitentitel ge&auml;ndert.");	
	$success=true;}
	if(isset($_POST["pages_enabled"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['pages_enabled']."' where name = 'pages_enabled'");
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['pages_enabled']."' where name = 'comments'");
		addlog("ADMIN: Paging ge&auml;ndert.");	
	$success=true;}
	if(isset($_POST["homepage"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['homepage']."' where name = 'homepage'");
		addlog("ADMIN: Homepage ge&auml;ndert.");	
	$success=true;}
	if(isset($_POST["comments"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['comments']."' where name = 'comments'");
		addlog("ADMIN: Kommentareinstellungen ge&auml;ndert.");	
	$success=true;}
	if(isset($_POST["profilepics"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_POST['profilepics']."' where name = 'profilepics'");
		addlog("ADMIN: Profilbildeinstellungen ge&auml;ndert.");	
	$success=true;}
	if(isset($_GET["shortcut"])) {
		$db->query("update ".$db_prefix."preferences set value = '".$_GET['shortcut']."' where name = 'shortcut'");
		addlog("ADMIN: Shortcut Icon ge&auml;ndert.");	
	$success=true;}
	if(isset($reload_site))
		echo "<script>window.location.href=window.location.href;</script>";	
	if(isset($success))
		echo "<div class='alert alert-success' role='alert'>".$text['changes_saved']."</div>";
	if(@$_GET['dropdown']==0) {
	?>
	<table width=100% id="adminprefs">
	<tr>
	<td width=33% style="vertical-align:top;">
	<form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text['welcomemsg_home']; ?>
	<br>
	<textarea onchange="this.form.submit()" name="welcome_msg" style="width:300px;height:50px;"><?php show("welcome_msg"); ?></textarea>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text['website_template']; ?>
	<br>
	<select onchange="this.form.submit()" name="website_template">
		<option value=""><?php echo $text['unchanged']; ?></option>
		<?php
		$templates = scandir('../templates');
		foreach ($templates as $template) {
			$selected="";
			if($template != ".." && $template != "." && $template != "random" && $template != "main" && $template != "/") {
				if(pref('website_template')==$template) {
					$selected="selected";
				}
			   echo "<option value='".$template."' $selected>".ucfirst($template) ."</option>";
			}
		}
		?>
	</select>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text['admin_template']; ?>
	<br>
	<select onchange="this.form.submit()" name="admin_template">
		<option value=""><?php echo $text['unchanged']; ?></option>
		<?php
		$templates = scandir('../templates');
		foreach ($templates as $template) {
			$selected="";
			if($template != ".." && $template != "." && $template != "random" && $template != "main" && $template != "/") {
				if(pref('admin_template')==$template) {
					$selected="selected";
				}
			   echo "<option value='".$template."' $selected>". ucfirst($template) ."</option>";
			}
		}
		?>	
	</select>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php if(pref("pages_enabled")=="yes") {$checked="checked";} echo ucfirst($text['modul']); echo '<br>
	<select onchange="this.form.submit()" name="pages_enabled">
		<option>'.$text['unchanged'].'</option>
		<option value="yes">'.$text['blogging'].'</option>
		<option value="no">'.$text['website'].'</option>
	</select>
	';?>
	<br>
	</form>
	
	<form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text["homepage"]; ?><br>
	<select onchange="this.form.submit()" name="homepage">
		<option value=""><?php echo $text['unchanged']; ?></option>
		<option value="homefeed"><?php echo $text['homefeed']; ?></option>
		<option value="article"><?php echo $text['article_home']; ?></option>
		<option value="empty"><?php echo $text['empty']; ?></option>
	</select>
	</form>
	<form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text["comments"]; ?><br>
	<select onchange="this.form.submit()" name="comments">
		<option><?php echo $text['unchanged']; ?></option>
		<option value="yes"><?php echo $text['article_specific']; ?></option>
		<option value="no"><?php echo $text['no']; ?></option>
	</select>
	</form>
	<form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text["profilepics"]; ?><br>
	<select onchange="this.form.submit()" name="profilepics">
		<option><?php echo $text['unchanged']; ?></option>
		<option value="yes"><?php echo $text['yes']; ?></option>
		<option value="no"><?php echo $text['no']; ?></option>
	</select>
	</form>
	</td><td width=33% style="vertical-align:top;">
	<?php echo $text['welcomeimg']; ?>
	<form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<select onchange="this.form.submit()" name="welcome_img_yn">
		<option><?php echo $text['unchanged']; ?></option>
		<option value="yes"><?php echo $text['yes']; ?></option>
		<option value="no"><?php echo $text['no']; ?></option>
	</select>
	</form>

	<?php	 if(pref("welcome_img_yn")=="yes") {?>
	<?php echo $text['welcomeimg'].$text["upload"]; ?> <br>
	<form style="font-size:4.5mm;" action="upload.php?img=welcome_img" method=post enctype="multipart/form-data">
	<input onchange="this.form.submit()" name="welcome_img" type=file>
	</form>
	<?php }?>
	
	<?php echo $text['headpicture']; ?>
	<form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<select onchange="this.form.submit()" name="headpicture_yn">
	<option><?php echo $text['unchanged']; ?></option>
	<option value="yes"><?php echo $text['yes']; ?></option>
	<option value="no"><?php echo $text['no']; ?></option>
	</select>
	</form>
	
	<?php	 if(pref("headpicture_yn")=="yes") {?>
	<?php echo $text['headpicture'].$text["upload"]; ?> <br>
	<form style="font-size:4.5mm;" action="upload.php?img=headpicture" method="post" enctype="multipart/form-data">
	<input onchange="this.form.submit()" name="headpicture" type=file>
	</form>
	<?php }?>
	
	<?php echo $text['background']; ?>
	<form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<select onchange="this.form.submit()" name="background_yn">
	<option><?php echo $text['unchanged']; ?></option>
	<option value="yes"><?php echo $text['yes']; ?></option>
	<option value="no"><?php echo $text['no']; ?></option>
	</select>
	</form>
	
	<?php	 if(pref("background_yn")=="yes") {?>
	<?php echo $text['background'].$text["upload"]; ?> <br>
	<form style="font-size:4.5mm;" action="upload.php?img=background" method=post enctype="multipart/form-data">
	<input onchange="this.form.submit()" name="background" type=file>
	</form>
	<?php }?>
	
	<?php echo $text['shortcut']; ?> <br>
	<form style="font-size:4.5mm;" action="upload.php?img=shortcut" method=post enctype="multipart/form-data">
	<input onchange="this.form.submit()" name="shortcut" type=file>
	</form>
	
	</td><td width=33% style="vertical-align:top;">
	<form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text['pref_l']; ?>
	<br>
	<select onchange="this.form.submit()" name="language">
		<option value=""><?php echo $text['unchanged']; ?></option>
		<?php
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
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text["article_suggestions"]; ?><br>
	<select onchange="this.form.submit()" name="aside">
		<option value=""><?php echo $text['unchanged']; ?></option>
		<option value="yes"><?php echo $text['yes']; ?></option>
		<option value="no"><?php echo $text['no']; ?></option>
	</select>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text["animations"]; ?><br>
	<select onchange="this.form.submit()" name="animations">
		<option value=""><?php echo $text['unchanged']; ?></option>
		<option value="no"><?php echo $text['no']; ?></option>
		<option value="yes"><?php echo $text['yes']; ?></option>
	</select>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text["search"]; ?><br>
	<select onchange="this.form.submit()" name="search">
		<option value=""><?php echo $text['unchanged']; ?></option>
		<option value="yes"><?php echo $text['yes']; ?></option>
		<option value="no"><?php echo $text['no']; ?></option>
	</select>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=0" method="post">
	<?php echo $text["width_container"]; ?><br>
	<select onchange="this.form.submit()" name="width_container">
		<option value=""><?php echo $text['unchanged']; ?></option>
		<option value="800px"><?php echo $text['small']; ?></option>
		<option value="900px"><?php echo $text['normal']; ?></option>
		<option value="1000px"><?php echo $text['large']; ?></option>
		<option value="1200px"><?php echo $text['very_large']; ?></option>
		<option value="90%"><?php echo $text['fullscreen']; ?></option>
	</select>
	</form>
	</td></tr></table>
	<?php
	}
	if(@$_GET['dropdown']==1) {
	echo '<form style="font-size:4.5mm;" action="?action='.@$_GET['action'].'&dropdown='.@$_GET['dropdown'].'" method=post>';
	?>
	<?php echo $text['user_name']; ?>
	<br>
	<?php echo '<input onchange="this.form.submit()" name="user_name" value="'.pref("user_name").'">'; ?>
	<br>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=1" method=post>
	<?php echo $text['user_email']; ?>
	<br>
	<?php echo '<input onchange="this.form.submit()" name="user_email" value="'.pref("user_email").'">'; ?>
	<br>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=1" method=post>
	<?php echo "Admin - ".$text['password']; ?>
	<br>
	<?php echo '<input onchange="this.form.submit()" name="user_password" placeholder='.$text['optional'].'>'; ?>
	<br>
	</form><form style="font-size:4.5mm;" action="?action=9&dropdown=1" method=post>
	<?php echo $text["website_title"]; ?>
	<br>
	<?php echo '<input onchange="this.form.submit()" name="website_title" value="'.pref("website_title").'" placeholder='.$text['website_title'].'>'; ?>
	<br>
	</form>
	<br>
	</form>
	</td>
	</tr>
	</table>
	<?php
	}
	if(@$_GET['dropdown']==2) {
		echo "<pre>";
		if(@$_GET["path"]!="" && @$_GET["path"]!="..") {
			$layers=explode("/",@$_GET["path"]);
			echo "<a href=?action=9&dropdown=2&path=".substr(@$_GET["path"],0,(strlen(@$_GET["path"])-strlen($layers[(count($layers)-1)])-1))." class=btn-comis style=display:inline-block;margin-top:-20px;margin-bottom:10px; >&larr; ".$text["back"]."</a>\n";
		}
		(@$_GET["path"]=="")?@$_GET["path"]="..":false;
		foreach(glob(@$_GET["path"]."/*") as $file) {
			if(substr($file,(strlen($file)-1),1)!="~") {
				$dispfile=substr($file,strlen(@$_GET["path"])+1);
				if(is_dir($file))
					echo "<a href='?action=9&dropdown=2&path=".str_replace("/","/",$file)."' class=icon-dir>".$dispfile."</a><!--<a href='?action=200&parent_action=9&delete=".$file."&return=?action=9(and)dropdown=2(and)path=".@$_GET["path"]."' style=float:right><img src=../templates/main/images/delete.png>--></a>\n";		
				else {
					$ending=explode(".",$dispfile);$ending=$ending[(count($ending)-1)];
					$endings_editable=array("html","php","css","js","bckp","log","ori");
					if(is_sudo) $endings_editable[]="conf";
					$endings_link=array("jpeg","jpg","png","zip","gif","ico");
					$icon_img=array("jpeg","png","jpg","gif","ico");
					if(in_array($ending,$endings_editable)) $href="?action=200&parent_action=9&file=".$file."&return=?action=9(and)dropdown=2(and)path=".@$_GET["path"];
					if(in_array($ending,$endings_link)) $href="../".substr($file,3);
					$icon=(in_array($ending,$icon_img))?"img":"none";
					($icon=="none" && in_array($ending,$endings_editable))?$icon="file":false;
					echo "<a ";
					echo($ending=="zip")?"style=background-image:url(../templates/main/images/icon_backup.gif); ":false;
					echo ($href!="")? "href='".$href."'":false;
					echo " class='icon-unknown icon-".$icon."'>".$dispfile." [".strtoupper($ending)."-Datei]</a><a href='?action=200&parent_action=9&delete=".$file."&return=?action=9(and)dropdown=2(and)path=".@$_GET["path"]."' style=float:right>".ucfirst($text["delete"])."&emsp;<img src=../templates/main/images/delete.png style=margin-bottom:-6px;></a>\n";
				}
			}
		}
		echo "</pre>";
	}
}
if(@$_GET['action']==10 && is_sudo()) {
	echo "<style>#admincontainer{padding:0 !Important}</style><iframe width=100%  frameborder=0 style=height:100%; src=//domiscms.de/updates.php?v=$v&l=".$l."><a href=//domiscms.de/updates.php?v=$v&l=".$l.">hier</a></iframe><div style='text-align:center;margin-top:-150px;'></div>";
}
if(@$_GET['action']==11 && is_sudo()) {
	echo "<h1 style=margin-top:0;>".$text["backup"]."</h1><p>".$text["backups"]."</p><hr style=background:hsla(220,100%,50%,.2)>";
	$timestamps=file("../log/backup_timestamps.log");
	$show=@$_GET["show"];
	$i=0;
	if($show=="my_backups") {
		$names=file("../log/backup_names.log");
		foreach($names as $name) { 
			if(file_exists("../backup/".substr($name,0,strlen($name)-1).".bckp"))
			echo "<a href='../bin/timetravel.php?name=".$name."&fallback=backup&show=".@$_GET["show"]."' class=changeopacity style=color:darkblue;text-decoration:none;><div style='padding:1px'><img src=../templates/main/images/icon_backup.gif style='margin:0 10px;margin-top:5px;'>".$text["name"].": ".$name."</div></a><hr style=background:hsla(220,100%,50%,.2)>";
		}
	}
	elseif(@$_GET["show"]=="import") {
		echo '<form enctype="multipart/form-data" style="font-size:4.5mm;" action="../bin/import.php?options" method="post">';
		echo "<h2>".ucfirst($text["import"])."</h2>
		<input name='file' placeholder='".$text["file"]."' type=file><br><br>
		<input name='import_mainpage' value='yes' type=checkbox> import mainpage.php<br>
		<input name='import_db' value='yes' type=checkbox> import db.conf<br>
		<input name='import_aboutconf' value='yes' type=checkbox> import about.conf<br>
		<input name='import_mainconf' value='yes' type=checkbox checked> import main.conf<br>
		<input name='import_instconf' value='yes' type=checkbox checked> import inst.conf<br>
		<input name='import_mysql_tables' disabled value='yes' type=checkbox checked> import mysql tables<br>
		<input name='options' value='true' type=hidden>
		<br><input name='upload' value='".$text["import"]."' type='submit'>
		</form>
		";
	}
	elseif($show=="create_backup") {
		echo "
		<form action=\"../bin/backup.php\">
			<b style=\"color:#333;margin:10px 0px;display:block;\">".$text["backup_name"].":</b>
			<input name=name placeholder='".$text["backup_name"]."'>
			<input value='".$text["create_backup"]."' type=submit>
		</form>
		";
	}/*
	elseif(isset($_POST["importsubmit"])) {
		smkdir("../files/tmp");
		$file = "log/back/".time()."-".$_FILES['welcome_img']['name'];
		move_uploaded_file($_FILES['welcome_img']['tmp_name'], "../".$file);
	}*/
	elseif($show=="import") {
		echo "
		<form action=\"../bin/backup.php\">
			<b style=\"color:#333;margin:10px 0px;display:block;\">".$text["select_file"].":</b>
			<input name=name placeholder='".$text["backup_name"]."'>
			<input value='".$text["upload"]."' type=submit>
		</form>
		";
	}
	else {
		foreach($timestamps as $ts) {
			$out="<a href='../bin/timetravel.php?timestamp=".str_replace(PHP_EOG,null,$ts)."&fallback=backup&show=".@$_GET["show"]."' class=changeopacity style=color:darkblue;text-decoration:none;><div style='padding:1px'><img src=../templates/main/images/icon_backup.gif style='margin:0 10px;margin-top:5px;'>".$text["backup_date"].date("d.m.Y, H:i:s",$ts)."</div></a><hr style=background:hsla(220,100%,50%,.2)>";
			if($show=="daily") {
				if(date("d.m.Y",$ts)!=$last) {
					echo $out;
					$last=date("d.m.Y",$ts);
				}
			}
			elseif($show=="download") {
				echo "<a href='../bin/timetravel.php?timestamp=".str_replace(PHP_EOG,null,$ts)."&fallback=backup&show=".@$_GET["show"]."' class=changeopacity style=color:darkblue;text-decoration:none;><div style='padding:1px'><img src=../templates/main/images/icon_backup_download.gif style='margin:0 10px;margin-top:5px;'>".$text["backup_date"].date("d.m.Y, H:i:s",$ts)."</div></a><hr style=background:hsla(220,100%,50%,.2)>";
			}
			else {
				echo $out;
			}
		}
	}
}
if(@$_GET['action']==100 && is_sudo()) {
	$_SESSION["dev"]==true;
	$about=import_comis("../etc/about.conf");
	$dbconf=import_comis("../etc/db.conf");
	$inst=import_comis("../etc/inst.conf");
	echo "<h2>Information about your COMIS</h2><table class='preflist' cellspacing=0>
	<tr><td>Author</td><td>".$about["author"]."</td></tr>
	<tr><td>Version</td><td>".$about["version"]."</td></tr>
	<tr><td>Name</td><td>".$about["name"]."</td></tr>
	<tr><td>ID</td><td>".$about["id"]."</td></tr>";
	echo "<tr><td colspan=2></td></tr>";
	echo "<tr><td>DB Host</td><td>".$dbconf["db_host"]."</td></tr>
	<tr><td>DB Username</td><td>".$dbconf["db_username"]."</td></tr>
	<tr><td>DB Name</td><td>".$dbconf["db_name"]."</td></tr>
	<tr><td>DB Prefix</td><td>".$dbconf["db_prefix"]."</td></tr>";
	echo "<tr><td colspan=2></td></tr>";
	$result = $db->query("select * from `".$db_prefix."preferences`");
	while($row = $result->fetch_assoc()) {
		if($row[2]=="yes" || $row[2]=="true" || $row[1]=="1" || $row[1]=="TRUE") $row2="<b>".$row[2]."</b>";
		else $row2=$row["value"];
		echo "<tr><td>".$row["name"]."</td><td>".$row2."</td></tr>";
	}
	echo "<tr><td colspan=2></td></tr>";
	$conf=file("../etc/main.conf");
	array_shift($conf);
	foreach($conf as $row) {
		$row=explode("=", $row);
		$row[1]=str_replace("\n", "", $row[1]);
		$row[1]=str_replace("\t", "", $row[1]);
		if($row[1]=="yes" || $row[1]=="true" || $row[1]=="1" || $row[1]=="TRUE") $row1="<b>".$row[1]."</b>";
		else $row1=$row[1];
		echo "<tr><td>".$row[0]."</td><td>".$row1."</td></tr>";
	}
	$addons=glob("../addons/*");
	echo "<tr><td colspan=2></td></tr><tr><td style=vertical-align:top>".$text["addons"]."</td><td style=vertical-align:top>";if($addons)foreach($addons as $file){$f=explode("/",$file);$f=explode(".php",$f[(count($f)-1)]);echo$f[0]."<br>";}echo "</td></tr>";
	echo "</table><br>";
	if ($_SESSION['exha']=="" || $_COOKIE['username']=="admin" )
	echo "<a href=?action=101 class=btn-comis>>> Set up External help access <<</a><br><br>";
	echo "<a href=?action=102 class=btn-comis>>> PHP INFO <<</a><br><br>";
	echo "<br><br><br>";
}
if(@$_GET['action']==101 && ($_SESSION['exha']=="" || is_sudo())) {
	if(@$_GET["h"] <= 96 && @$_GET["h"] > 0)
		$h = @$_GET["h"];
	else
		$h = 2;
	if(@$_GET["security"] <= 32 && @$_GET["security"] >= 6)
		$security = @$_GET["security"];
	else
		$security = 16;
	if(@$_GET["seed"]>=1000000 && @$_GET["seed"]<=99999999)
		$seed=@$_GET["seed"];
	else
		$seed=rand(0,999999999);
	$code=substr(md5(rand(0,999999999)+$seed),0,$security);
	file_put_contents("../etc/exha.conf","#preflist\ncode=".md5(strtoupper($code))."\nexpire=".(time()+(3600*$h))."\nsince=".(time()));
	if(@file_get_contents("../etc/exha.conf")=="#preflist\ncode=".md5(strtoupper($code))."\nexpire=".(time()+(3600*$h))."\nsince=".(time())) {
		echo "This code can be used onced to get fully admin access for the next ".$h." hours, so until ".date("D, d M H:i",(time()+(3600*$h)))." !<br><br>CODE: <span style='letter-spacing:2px;border-radius:5px;padding: 3px 7px;background:white;border:1px solid black;'><tt>".strtoupper($code)."</tt></span>
		<br><br>
		This will be the URL:<br> <a href='".$_SERVER["HTTP_REFERER"]."&exha=".strtoupper($code)."'>".$_SERVER["HTTP_REFERER"]."&exha=".strtoupper($code)."</a>
		";
		echo "<br><br><form>
		<input onchange=\"this.form.submit()\" type=number min=1 max=96 value='".$h."' name=h> Hours of Admin Access<br><br>
		<input onchange=\"this.form.submit()\" type=number min=6 max=32 value='".$security."' name=security> Security Level<br><br>
		<input onchange=\"this.form.submit()\" type=number min=1000000 max=99999999 value='".$seed."' name=seed> Random Seed
		<input type=hidden value=101 name=action>
		<form>
		";
	}
	else {
		echo "ERROR";
	}
}
if(@$_GET['action']==102 && is_sudo()) {
	phpinfo();
}
if(@$_GET["action"]=="200" && is_admin()) {
	if(isset($_GET["delete"]) && conf("global_delete")) {
		unlink(@$_GET["delete"]);
		echo "<script>window.location.href='".str_replace("(and)","&",@$_GET["return"])."&true';</script>";
	}
	else {
		if(isset($_POST["save"])) {
			$_POST["data"]=str_replace("&lt;", "<", $_POST["data"]);
			$_POST["data"]=str_replace("&gt;", ">", $_POST["data"]);
			$_POST["data"]=str_replace("&quot;", "\"", $_POST["data"]);
			$_POST["data"]=str_replace("&#039;", "'", $_POST["data"]);
			$_POST["data"]=str_replace("&amp;", "&", $_POST["data"]);
			$_POST["data"]=str_replace("&#039;", "&", $_POST["data"]);
			if(file_put_contents($_POST["file"],$_POST["data"]))
				echo "<script>window.location.href='".str_replace("(and)","&",$_POST["return"])."&true';</script>";
			else
				echo "<script>window.location.href='".str_replace("(and)","&",$_POST["return"])."&false';</script>";
		}
		else {
			if(!file_exists(@$_GET["file"])) {
				error(1413);
				return;
			}
			echo "<form method=post action=?action=200>
			<h2 stye=display:inline>Editing: ".@$_GET[""]."<input name=file value='".@$_GET["file"]."' style=display:inline-block;margin-bottom:10px;margin-left:10px;width:90%></h2>
			<textarea style='width:100%;height:500px' name=data>".str_replace("</textarea>","&lt;/textarea>",@file_get_contents(@$_GET["file"]))."</textarea><br><br>
			<input type=hidden name=return value='".@$_GET["return"]."'>
			<input type=submit name=save value='".$text["save"]."'>
			</form>";
		}
	}
}
?>
<script type="text/javascript">
function reportbug(lang) {
	if (lang=="de") {
		msg = prompt("Bitte beschreiben Sie den Fehler","");
	}
	else {
		msg = prompt("Please describe the Bug","");
	}
	window.document.getElementById("sendmail").src='../includes/sendmail.php?msg='+msg+"&l="+lang+"&attach=all";
}
</script>
<iframe style="display:none;" id="sendmail"></iframe>
</div>

<?php
	$data = "<?php
	//AUTOGENERATED FILE TO RELOAD COMIS ON ALL DEVICES
	session_start();
	\$_SESSION['refresh']=\"".rand(1,9999999)."\";
	echo \"#\".\$_SESSION['refresh'].\"#\";
	if(\$_SESSION['refresh_true']==\$_SESSION['refresh']) {
	}
	else {
		\$_SESSION['refresh_true']=\$_SESSION['refresh'];
		echo '<script type=\"text/javascript\">
	window.location.href=window.location.href;
	</script>';
	}
	?>
	";
	$dateihandle = fopen("../bin/refresh.php","w");
	fwrite($dateihandle, $data);
	if(isset($_GET["install"]) && is_sudo() && conf("open_external_url")) {
		$contents=file_get_contents("http://domiscms.de/addons/".$_GET["install"].".zip");
		echo "<div style=\"background:url(../templates/main/images/bg.jpeg)\" class=\"fullscreen\"><h1 style=\"margin-top:300px;\">".$text["connecting"]." <img src=../templates/main/images/loading.gif></h1></div>";
		/*if(isset($_GET["check_updates"])) {
			if(@file_get_contents("http://domiscms.de/developer/uploads/".@$_GET["install"].".zip")!="") {}
			elseif(@file_get_contents("http://domiscms.de/downloads/updates/comis_update_from_".str_replace("\r","",$v).".zip")!="") {
				if(file_put_contents("tmp.zip",@file_get_contents("http://domiscms.de/downloads/updates/comis_update_from_".str_replace("\r","",$v).".zip"))) {
				   rename("../etc/db.conf","../etc/db.conf.tmp");
				   rename("../etc/inst.conf","../etc/inst.conf.tmp");
				   rename("../etc/private.conf","../etc/private.conf.tmp");
				   if(strstr(substr(@file_get_contents("../includes/mainpage.php"),0,50),"$dont_change_on_auto_update=true"))
					   rename("../includes/mainpage.php","../includes/mainpage.php.tmp");
					$zip = new zipArchive();
					$result = $zip->open("tmp.zip");
					if ($result === TRUE) {
					    $zip ->extractTo("../");
					    $zip ->close();
					    rename("../installation/index.php","../installation/reinstall.php");
					    unlink("../etc/db.conf");
					    unlink("../etc/inst.conf");
					    unlink("../etc/private.conf");
						   if(strstr(substr(@file_get_contents("../includes/mainpage.php"),0,50),"$dont_change_on_auto_update=true"))
							   unlink("../includes/mainpage.php");
					    rename("../etc/db.conf.tmp","../etc/db.conf");
					    rename("../etc/inst.conf.tmp","../etc/inst.conf");
					    rename("../etc/private.conf.tmp","../etc/private.conf");
						   if(strstr(substr(@file_get_contents("../includes/mainpage.php"),0,50),"$dont_change_on_auto_update=true"))
							   rename("../includes/mainpage.php.tmp","../includes/mainpage.php");
					    if(@file_get_contents("../installation/update.php")=="")
						    echo "<meta http-equiv=refresh content='1,../'>";
						 else
						    echo "<meta http-equiv=refresh content='1,../installation/update.php'>";
					    unlink("tmp.zip");
					}
				}
			}
			else {
				alert($text["no_updates"]);
			}
			return;
		}*/
		if($contents!="") {
			if(!strstr(@file_get_contents("../etc/signatures.list"),md5($contents)) && !strstr(@file_get_contents("http://domiscms.de/addons/signatures.list"),md5($contents))) {
				alert("No signature! Installation aborted! If you want to install though please change your main.conf and set require_signature from true to false. Normaly you would be able now, to choose between two options, but because of the stupidness of the device this programm was coded on this option is not avaiable. We're not sorry! Add this ".md5($contents)."  as a new line to your etc/sources.list, and repeat the installation.");
				echo "<meta http-equiv=refresh content='0,?action=8'>";
			}
			else {
				smkdir("../files/tmp");
				$uniqid=uniqid();
				echo "<div style=\"background:skyblue\" class=\"fullscreen\"><h1 style=\"margin-top:300px;\">".$text["connecting"]." <img src=../templates/main/images/loading.gif></h1></div>";
				if(file_put_contents("../files/tmp/".@$_GET["install"].$uniqid.".zip",$contents)) {
					$zip = new zipArchive();
					$result = $zip->open("../files/tmp/".@$_GET["install"].$uniqid.".zip");
					if ($result === TRUE) {
						$zip ->extractTo("../addons");
						$zip ->close();
						unlink("../files/tmp/".@$_GET["install"].$uniqid.".zip");
					}
				}
				echo "<div style=\"background:lightgreen;\" class=\"fullscreen\"><h1 style=\"margin-top:300px;\">".$text["addon_installed"]."</h1></div>";
				echo "<meta http-equiv=refresh content='1,?action=8'>";
				return;
			}
		}
		else {
			echo "<div style=\"background:lightgreen;\" class=\"fullscreen\"><h1 style=\"margin-top:300px;\">";error(1600);echo "</h1><p>(Sub";error(16000000000000+@$_GET["install"]);echo ")</p></div>";
			echo "<meta http-equiv=refresh content='10,?action=8'>";
		}
	}
	echo "</div>";
	$addon=glob("../addons/*.admin.php");if($addon)foreach($addon as $file)include$file;
?>
<script type="text/javascript">w=1;function goathail(){if(w==10){window.open("http://domiscms.de/images/goathail.jpg", '_blank');w=0;}w=w+1;};</script>
</body>
</html>
