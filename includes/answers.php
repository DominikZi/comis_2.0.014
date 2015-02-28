<?php
if(isset($_POST['answer'])) {
	$_SESSION['answer']=$_POST['answer'];
}
?>
<hr><h4 style="text-align:center;"><?php echo $text["answers"]; ?></h4>
<?php echo '<form action="?forum=question&id='.$_GET['id'].'" method="post" style=margin-bottom:10px;><table style=margin-bottom:6px; width=100%><tr><td><input name=answer value="'.$_SESSION['answer'].'" style="width:100%;background:hsla(0,0%,100%,.5);border:1px solid hsla(0,0%,100%,.9);" type=text></td><td> &nbsp;&nbsp;&nbsp;</td><td><input type=submit value='.$text["post"].' style="width:100%;"></form></td></tr></table></center>'; ?>
<?php
if(isset($_POST['answer'])) {
	if($_COOKIE['username'] != "") {
		if(strstr($_POST['answer'],"<")) {
			echo "<script>alert('".$text["no_html"]."');</script>";
		}
		else {
			if(mysql_query("insert into `".$db_prefix."forum_answers`(`userid`,`questionid`,`answer`) values('".$_COOKIE['userid']."','".$_GET['id']."','".$_POST['answer']."')")) {
				$_SESSION['answer']="";			
			}
		}
	}
	else {
		echo $text["for_comments"]." <a href=?inc=login>".$text["login"]."</a> ".$text["or"]." <a href=?inc=register>".$text["register"]."</a>.";	
	}
}
$result = mysql_query("select * from ".$db_prefix."forum_answers where questionid = '".mysql_real_escape_string($_GET['id'])."' order by timestamp desc");
if($result) {
	while($comment = mysql_fetch_array($result)) {
		$users = mysql_query("select * from ".$db_prefix."user where id = '".$comment[userid]."' limit 1");
		if($users) {
			while($user = mysql_fetch_array($users)) {
				$username = $user[username];
			}
		}
		echo "<div class=comment>".ucfirst($username)."<br>".ucfirst($comment[answer])."</div>";
		$comments = true;	
	}
}
if(!$comments) {
	echo "<br><center><i style=opacity:.4;>".$text["no_comments_yet"].".</i><br><br>";	
}
?>