<?php
if($_COOKIE["username"]!="") {
	if($_POST['usersubmit'] == $text["save"]) {
		if($_FILES['profilepic']['name']!="" && pref("profilepics")!="no") {
			if($_FILES['profilepic']['type']=="image/jpeg")
				$ending=".jpeg";
			elseif($_FILES['profilepic']['type']=="image/png")
				$ending=".png";
			elseif($_FILES['profilepic']['type']=="image/gif")
				$ending=".gif";
			else {
				$ending=".jpg";
			}
			if(filesize($_FILES['profilepic']['tmp_name']) > 700000) {
				alert($text["smaller_than"]);
				error(1410);
				$return=true;
			}
			if(!$return) {
				if(!is_dir("files/profilepics/")) mkdir("files/profilepics/");
				$file = "files/profilepics/".time().rand(0,999999).$ending;
				$result = $db->query("select * from ".$db_prefix."user where id = '".$_COOKIE['userid']."'");
				while($row=$result->fetch_object()) {
					$trashfile=$row->profilepic;
				}
				if(move_uploaded_file($_FILES['profilepic']['tmp_name'], $file)) {
					if(conf("delete_trashfiles")) unlink($trashfile);
					else {smkdir(conf("trashpath"));rename($trashfile,conf("trashpath").time().rand(0,99999999999).ending("../".$trashfile));}
					mysql_query("update ".$db_prefix."user set `profilepic` = '".$file."' where id = '".$_COOKIE['userid']."'");
				}
			}
		}
		if(!$return && mysql_query("update ".$db_prefix."user set `name` = '".$_POST['name']."', `email` = '".$_POST['email']."' where id = '".$_COOKIE['userid']."'")) {
			echo "<div class='alert alert-success'>".$text['changes_saved']."</div>";
			$continue=true;
			addlog("BENUTZER: Benutzer Profil ".$_POST['title']." bearbeitet.");
		}
		else {
			echo "<div class='alert alert-danger'>".$text['error']."</div>";
			$continue=true;
			addlog("FEHLER: Benutzer Profil ".$_POST['title']." nicht bearbeitet.");
		}
		if($_POST['password']!="") {
			mysql_query("update ".$db_prefix."user set `password` = '".md5($_POST['password'])."' where id = '".$_COOKIE['userid']."'");	
		}
	}
	else {
		$continue=true;
	}
	if($continue) {
		echo '<form style="font-size:4.5mm;" action="?inc=account" method="post" enctype="multipart/form-data">';
		$result = mysql_query("select * from ".$db_prefix."user where username = '".$_COOKIE['username']."'");
		while($row = mysql_fetch_array($result)) {
			$result2 = mysql_query("select * from ".$db_prefix."groups order by name desc");
			echo "<h2>".$text['user2']." ".$row[username]." ".$text['user3']."</h2>";
			if(pref("profilepics")!="no") {
				echo $text['profilepic']."<br>
				<input style=width:400px; type='file' name='profilepic' id='profilepic'>
				<br><br>";
			}
			echo $text['name']."<br>
			<input style=width:400px; name='name' value='".$row[name]."'>
			<br><br>
			".$text['username']."<br>
			<input style=width:400px; name='username' value='".$row[username]."' disabled>
			<br><br>
			".$text['email']."<br>
			<input style=width:400px; type=email name='email' value='".$row[email]."'>
			<br><br>
			".$text['new_password']."<br>
			<input style=width:400px; onmouseover='this.type=\"text\"' onmouseout='this.type=\"password\"' name='password' placeholder='".$text['new_password']." (".$text['optional'].")'>
			<br><br>
			<input name='usersubmit' value='".$text["save"]."' type='submit'>
			</form>";	
			}
	}
}
?>