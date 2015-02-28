<?php
if(strstr($_POST["msg"],"'") | strstr($_POST["msg"],'"')) {alert($text["no_sqlinjection"]);return;}
if(isset($_POST['msg'])) {
	$_SESSION['msg']=$_POST['msg'];
}
?>
<hr><h4 style="text-align:center;"><?php echo $text["comments"]; ?></h4>
<?php echo '<form action="?article='.$_GET['article'].'" method="post" style=margin-bottom:10px;><table style=margin-bottom:6px; width=100%><tr><td><input name=msg value="'.$_SESSION['msg'].'" style="width:100%;background:hsla(0,0%,100%,.5);border:1px solid hsla(0,0%,100%,.9);" type=text></td><td> &nbsp;&nbsp;&nbsp;</td><td><input type=submit value="'.$text["post"].'" style="width:100%;"></form></td></tr></table></center>'; ?>
<?php
if(isset($_POST['msg'])) {
	if($_COOKIE['username'] != "") {
		if(strstr($_POST['msg'],"<")) {
			echo "<script>alert('".$text["no_html"]."');</script>";
		}
		else {
			if(mysql_query("insert into `".$db_prefix."comments`(`userid`,`timestamp`,`articleid`,`msg`) values('".$_COOKIE['userid']."','".time()."','".$_GET['article']."','".$_POST['msg']."')")) $_SESSION['msg']="dsa";
		}
	}
	else {
		echo $text["for_comments"]." <a href=?inc=login>".$text["login"]."</a> ".$text["or"]." <a href=?inc=register>".$text["register"]."</a>.";	
	}
}
$result = mysql_query("select * from ".$db_prefix."comments where articleid = '".mysql_real_escape_string($_GET['article'])."' order by timestamp desc");
if($result) {
	while($row = mysql_fetch_array($result)) {
		$users = mysql_query("select * from ".$db_prefix."user where id = '".$row[userid]."' limit 1");
		if($users) {
			while($user = mysql_fetch_array($users)) {
				$username = $user[username];
				$profilepic = $user[profilepic];
			}
		}
		$msg = $row[msg];
		if(strstr($row[msg],"https://www.youtube.com/watch?v=")) {
			$part=explode("https://www.youtube.com/watch?v=",$msg);
			$part=explode(" ",$part[1]);
			$msg="<div class=comment".$row[id].">Youtube Video // <a style=cursor:pointer onmouseup=\"$('.comment".$row[id]."').html('".str_replace("&#39;","",str_replace("&qout;","",$msg))."');\">".$text["show_me_comment"]."</a>".'<iframe style="margin-top:12px;opacity:.93;margin-bottom:-18px;border-radius:5px" width="100%" height="315" src="https://www.youtube.com/embed/'.$part[0].'" frameborder="0" allowfullscreen></iframe></div>';
		}
		echo "<div class='comment'>";if(pref("profilepics")!="no") {echo"<div class='profilepic'"; if($profilepic!="") echo " style='background-image:url(\"".$profilepic."\");'"; echo "></div>";}echo"<b>".ucfirst($username)."</b><br>".$msg."&nbsp;</div>";
	$rows = true;	
	}
}
if(!$rows) {
	echo "<br><center><i style=opacity:.4;>".$text["no_comments_yet"].".</i><br><br>";	
}
?>