<?php
if(isset($_POST['register'] ) && $_POST['email'] != "") {
	$result = mysql_query("select * from ".$db_prefix."user where username like '".$_POST['username']."' limit 1");
	while($row = mysql_fetch_array($result)) {
		$out.=" ".$text["username_is_already_there"];
		$already=true;
	}
	if(strstr($_POST["username"],"admin") || strstr($_POST["username"],"hack")) {
		$out.=" ".$text["username_is_already_there"];
		$already=true;
	}
	if(!preg_match("/^[a-zA-Z0-9]+$/s",$_POST["username"])) {
		$out.=" ".$text["username_is_not_avaiable"];
		$already=true;
	}
	if(!preg_match("/^[a-zA-Z0-9]+$/s",$_POST["username"])) {
		$out.=" ".$text["username_is_not_avaiable"];
		$already=true;
	}
	if(strlen($_POST['username']) < 3) {
		$out.=" ".$text['username_not_empty'];
		$already=true;
	}
	if(strlen($_POST['password']) < 3) {
		$out.=" ".$text['password2short'];
		$already=true;
	}
	if(!$already) {
		if(mysql_query("insert into ".$db_prefix."user(`name`,`username`,`email`,`password`,`timestamp`,`md5`,`profilepic`) values('".$_POST['name']."','".$_POST['username']."','".strtolower($_POST['email'])."','".md5($_POST['password'])."','".time()."','".md5(rand(1,9999999999999))."','".$_POST["profilepic"]."')")) {
			echo "<div class='alert alert-success'>".$text["registered_success"]."</div>";
			$form=false;
			return;
		}
		else {
			echo $text["register_failed"]."<br>";	
			$form=true;		
		}
	}
}
if($out)
echo "<div class='alert alert-danger'>".$out."</div>";
if(!$form) {
echo '<form id="registerform" action="?inc=login" method="post" id="login"><h2>'.ucfirst($text["register"]).'</h2>
<label>'.ucfirst($text["user_name"]).'</label><br>
<input name=name placeholder="'.ucfirst($text["name"]).'" value="'.$_POST["name"].'" required><br>
<label>'.ucfirst($text["username"]).'</label><br>
<input name=username placeholder="'.ucfirst($text["username"]).'" value="'.$_POST["username"].'" required><br>
<label>'.ucfirst($text["user_email"]).'</label><br>';
echo '<input name=email type="email" placeholder="'.ucfirst($text["email"]).'" value="'.$_POST["email"].'" required><br>
<label>'.ucfirst($text["password"]).'</label><br>';
echo '<input name=password type="password"placeholder="'.ucfirst($text["12"]).'" value="'.$_POST["password"].'" required><br>
<label>'.ucfirst($text["password"])." ".$text["repeat"].'</label><br>
<input name=password2 type="password" placeholder="'.ucfirst($text["12"])." ".$text["repeat"].'" required><br>'; 
if(pref("profilepics")!="no") {
	echo'<label>'.ucfirst($text["choose_profilepic"]).'</label><br><div id="profilepics" style="margin:10px;margin-bottom:30px;padding-left:0px;">';
	for($i=1;$i < 6;$i++) {
		echo "<div class='profilepic changeopacity' onmouseup=\"$('.visible').removeClass('visible');$('#profilepic').val('templates/main/images/profilepic".$i.".jpg');$(this).addClass('visible');\" style='cursor:pointer;opacity:.3;margin-left:.5px;width:35px;height:35px;background-image:url(templates/main/images/profilepic".$i.".jpg)';></div>";
	}
	echo '</div><br>';
}
echo '<br><input type=hidden name=profilepic id=profilepic></input>
<input type=submit style="width:;" value="'.ucfirst($text["register"]).'" name="register">
</form>';
}
?>