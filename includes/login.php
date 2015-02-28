<?php
if(isset($_POST['username']))$_POST['username']=strtolower($_POST['username']);
if(isset($_POST['register'])) {
	include("includes/register.php");
	return;
}
elseif(isset($_POST['login'])) {
	$query="select * from `".$db_prefix."user` where username like '".mysql_real_escape_string($_POST['username'])."' limit 1";
	$result = $db->query($query);
	if($result) {
		while($row = $result->fetch_assoc()) {
			if($row['password']==md5($_POST['password'])) {
				if($row['deactive']!="yes" | strtolower($_POST['username'])=="admin") {
					$_SESSION['username'] = $_POST['username'];
					$_SESSION['groupid'] = $row['groupid'];
					$_SESSION['userid'] = $row['id'];
					mysql_query("update ".$db_prefix."user set `last_login` = '".time()."' where id = '".$row[id]."'");
					echo '<meta http-equiv=refresh content=0,?do=login><script type="text/javascript">
					window.location.href=\'?do=login\';
					</script>';
				}
				else {
					echo "Dieses Konto ist deaktiviert.";
					return;
				}
				$result2 = $db->query("select * from ".$db_prefix."groups where id = '".$row['groupid']."' limit 1");
				if($result2) {
					while($row2 = $result2->dfetch_assoc()) {
						if($row2['edit_articles']=="yes" | $row2['edit_comments']=="yes" | $row2['edit_user']=="yes" | $row2['edit_pages']=="yes" | $row2['edit_groups']=="yes") {
							$_SESSION['adminaccess'] = "yes";
							$msg = " ".$text["do_you_want"]." <a href=admin>".$text["adminarea"]."</a>?";
						}
						else {
						}
					}
				}
				
				echo "<iframe style=display:none; src=login.php>".$text["please"]." <a href=login.php>".$text["here"]."</a> ".$text["click"]." .</iframe>";
				if(strtolower($_POST['username'])=="admin") {
					echo " <a href=admin>".$text["adminarea"]."</a>. ";	
				}
				return;
			}
		}
	}
	else {error(1502221400);}
	echo "<div class=\"alert alert-danger\">".$text["wrong_entrace_data"]."<a href=\"?inc=forgot\">".$text["pass_forgotten"]."</a></div>";
}
if(isset($_GET["success"])) {
	if($_COOKIE["username"]=="admin")$msg = $text["do_you_want"]." <a href=admin>".$text["adminarea"]."</a>?";
	else{
		if(date("H") >= 1 && date("H") <= "4" ) {
			$msg = $text["nice_2_cu"][7].date(" H ").$text["nice_2_cu"][8];
		}
		elseif(date("H") > 18) {
			$msg = $text["nice_2_cu"][5];
		}
		elseif(date("H") > 5 && date("H") < 8) {
			$msg = $text["nice_2_cu"][6];
		}
		else {
			$msg = $text["nice_2_cu"][rand(0,4)];
		}
	}
	echo "<iframe style=display:none; src=login.php>".$text["please"]." <a href=login.php>".$text["here"]."</a> ".$text["click"]." .</iframe>";
	echo "<h1 style='margin-bottom:0;'>".$text["hello"]." ".ucfirst($_COOKIE['username']).",</h1><br><span style='font-size:18px;'>".$text["successfully_logged_in"]."</span><br><br>$msg <br><br>".$text["your_last_login"]." ".date("d.m",$row[last_login])." ".$text["at"]." ".date("H:i",$row[last_login])." ".$text["clock"].".<br><br> <a href='?inc=account'>>>".$text["change_account"]."</a>";
	if(strtolower($_POST['username'])=="admin") {
		echo " <a href=admin>".$text["adminarea"]."</a>. ";	
	}
}
elseif(isset($_COOKIE['username']))
	echo $text["already_logged_in"];	
else {
	echo '<h2 style="margin-left:7px;margin-bottom:6px;">Login</h2>
	<form action="?inc=login" method="post" id="login">
	<input name=username value="'.@$_GET["username"].'" placeholder="'.ucfirst($text["username"]).'" required><br>
	<input name=password type="password" placeholder="'.ucfirst($text["password"]).'" required><br>
	<input type=submit style="width:82px;" value="'.ucfirst($text["login"]).'" name="login">
	<input type=submit style="width:130px;" value="'.ucfirst($text["register"]).'" name="register">
	</form>';
}
?>