<?php
$form = "<form action=\"?inc=forgot\" method=\"post\">
	<input name='email' type='email' placeholder='".$text[6]."'>
	<input type=submit value='".$text["send_pass"]."'>
	</form>";
if(isset($_GET["forgotten"]) || isset($_POST["forgotten"])) {
	if($_POST["new_pass"]==$_POST["new_pass2"] && strlen($_POST["new_pass2"]) > 0) {
		if($db->query("update `".$db_prefix."user` set password='".md5($_POST["new_pass2"])."' where md5 = '".$_POST["forgotten"]."'"))
		if($db->query("update `".$db_prefix."user` set  md5 = '". md5(uniqid().rand(rand(1,9999),rand(1,99999999999999999999))).rand(1,9999999999999) ."' where md5 = '".$_POST["forgotten"]."'"))
		echo "<h2>".$text["success"]."</h><p>".$text["success_pass"]."</p>";
		else
		error(1402);
	}
	else {
		if(strlen($_POST["new_pass2"]) > 0 || $_POST["new_pass"]!=$_POST["new_pass2"]) {echo "<div class='alert alert-danger'>".$text["37"]."</div>";}
		$result=$db->query("select * from `".$db_prefix."user` where `md5` = '".strtolower($_GET["forgotten"]).strtolower($_POST["forgotten"])."' limit 1");
		while($row=$result->fetch_assoc()) {
			echo "<h2>".$text["pass_reset"]."</h2><p>".$text["type_pass"]."</p>
			<form action=\"?inc=forgot\" method=\"post\">
			<input name='new_pass' type='pass' placeholder='".$text["12"]."' value='".$_POST["new_pass"]."'><br><br>
			<input name='new_pass2' type='pass' placeholder='".$text["12"]." ".$text["repeat"]."'><br><br>
			<input name='forgotten' type='hidden' value='".$_GET["forgotten"].$_POST["forgotten"]."'>
			<input type=submit value='".$text["save_pass"]."'>
			</form>";
		}
	}
}
elseif(!isset($_POST["email"]))
	echo "<h2>".$text["pass_forgotten"]."</h2><p>".$text["type_email"]."</p>".$form;
else {
	$result=$db->query("select * from `".$db_prefix."user` where email = '".strtolower($_POST["email"])."' limit 1");
	while($row=$result->fetch_assoc()) {
		$found=true;
		$to=strtolower($_POST["email"]);
	}
	if(isset($found)) {
		$md5=rand(0,99999).md5(rand(0,9999999999999999999)).md5(rand(0,9999999999999999999)).rand(0,99999999);
		if($db->query("update `".$db_prefix."user` set md5='".$md5."' where email = '".strtolower($_POST["email"])."'")) {
			$link=$_SERVER["HTTP_REFERER"]."&forgotten=".$md5;
			$subject=$text["email_pass_subject"];
			$message=$text["this_is_link"]."\n\n".$link;
			if(mail($to, $subject, $message)) {
				echo "<h2>".$text["pass_forgotten"]."</h2><p>".$text["email_sended"].".</p>";
			}
			else
			error(1401);
		}
		else
			error(1400);
	}
	else
		echo "<h2>".$text["pass_forgotten"]."</h2><p>".$text["email_not_sended"].".</p>".$form;
}
?>