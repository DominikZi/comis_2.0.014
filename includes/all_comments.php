<body style="background:#eee !important">
<link href="../templates/main/bootstrap.css" rel="stylesheet" type="text/css">
<?php
include('../db.php');
include("../language/".pref('language').".php");
echo '<link href="../templates/'.pref('admin_template').'/style.css" rel="stylesheet" type="text/css">';
echo '<link href="../templates/main/style.css" rel="stylesheet" type="text/css">';
?><div style=margin:14px;><?php
	$result2 = mysql_query("select * from ".$db_prefix."articles where comments='yes' order by timestamp desc limit 5");
	if($result2) {
		echo "<form action=?action=5 method=post><select name=article style=width:100% onchange='this.form.submit();'>";
		echo "<option value='' >".$text["all"]."</option>";
		while($row2 = mysql_fetch_array($result2)) {
			echo "<option value='".$row2[id]."' ";if($_POST["article"]==$row2[id]) echo "selected"; echo ">".$row2[title]."</option>";
		}
		echo "</select></form>";
	}
	if($_POST["article"]!="") $article="where `articleid` = '".$_POST["article"]."'";	
	$result = mysql_query("select * from ".$db_prefix."comments $article order by timestamp desc limit 5");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			$users = mysql_query("select * from ".$db_prefix."user where id = '".$row[userid]."' limit 1");
			if($users) {
				while($user = mysql_fetch_array($users)) {
					$username = $user[username];
				}
			}
			echo "<div class=comment>dsa".ucfirst($username)."<br>".ucfirst($row[msg])."</div>";
		$comments = true;	
		}
	}
	if(!$comments) {
		echo "<br><center><i style=opacity:.4;>".$text["no_comments_yet"].".</i><br><br>";	
	}
	