<?php
if($_COOKIE['username']=="admin" || $_COOKIE["adminaccess"]=="yes" || $_COOKIE["adminaccess"]=="admin_like") {
	print_r($_POST);
	if($_GET["img"]=="headpicture") {
		$file = "files/headpic-".time()."-".$_FILES['headpicture']['name'];
		move_uploaded_file($_FILES['headpicture']['tmp_name'], "../".$file);
		echo "<meta http-equiv=refresh content='0,index.php?action=9&Einstellungen&headpicture=$file'>";
	}
	if($_GET["img"]=="background") {
		$file = "files/bg-".time()."-".$_FILES['background']['name'];
		move_uploaded_file($_FILES['background']['tmp_name'], "../".$file);
		echo "<meta http-equiv=refresh content='0,index.php?action=9&Einstellungen&background=$file'>";
	}
	if($_GET["img"]=="shortcut") {
		$file = "files/shortcut-".time()."-".$_FILES['shortcut']['name'];
		move_uploaded_file($_FILES['shortcut']['tmp_name'], "../".$file);
		echo "<meta http-equiv=refresh content='0,index.php?action=9&Einstellungen&shortcut=$file'>";
	}
	else {
		if($_GET['action']!="") {
			$file = "files/".time()."-".$_FILES['welcome_img']['name'];
			move_uploaded_file($_FILES['welcome_img']['tmp_name'], "../".$file);
			echo "<meta http-equiv=refresh content='1,index.php?action=".$_GET['action']."&dropdown=".$_GET['action']."&file=$file'>";
		}
		else {
			$file = "files/".time()."-".$_FILES['welcome_img']['name'];
			move_uploaded_file($_FILES['welcome_img']['tmp_name'], "../".$file);
			echo "<meta http-equiv=refresh content='1,index.php?action=9&Einstellungen&welcome_img=$file'>";
		}
	}
}
else {
	echo "You have to be Admin // Bitte melden Sie sich als Admin an.";	
}
?>