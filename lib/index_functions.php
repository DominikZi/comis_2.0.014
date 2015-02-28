<?php
class index {
	function do_what($what) {
		if($what=="login") {
			setcookie("username","",time()-3600);
			setcookie("userid","",time()-3600);
			setcookie("groupid","",time()-3600);
			setcookie("id","",time()-3600);
			setcookie("adminaccess","",time()-3600);
			if(isset($_GET['admin']) | isset($_GET['website'])) {
				setcookie("username",$_SESSION['username'],time()+7200);
			}
			else {
				setcookie("username",$_SESSION['username'],time()*2);
			}
			setcookie("groupid",$_SESSION['groupid'],time()*2);
			setcookie("userid",$_SESSION['userid'],time()*2);
			setcookie("id",$_SESSION['userid'],time()*2);
			setcookie("adminaccess",$_SESSION['adminaccess'],time()*2);
			$_SESSION['adminaccess'] = false;
			$_SESSION['groupid'] = false;
			$_SESSION['username'] = false;
			$_SESSION['userid'] = false;
			if(isset($_GET['admin'])) {echo "<meta http-equiv=refresh content=0,admin>";}
			elseif(isset($_GET['website'])) {echo "<meta http-equiv=refresh content=0,index.php>";}
			else {echo "<meta http-equiv=refresh content=0,?inc=login&success>";}
			echo '<body style="background-size:cover !important;background-color:#457c9a !important;background-image:url(templates/main/images/bg.jpeg) !important;">';
		}
		if($what=="logout") {
			if(setcookie("username","",time()-3600)&&
			setcookie("userid","",time()-3600)&&
			setcookie("groupid","",time()-3600)&&
			setcookie("id","",time()-3600)&&
			setcookie("adminaccess","",time()-3600)) {
			echo '<body style="background-size:cover !important;background-color:#457c9a !important;background-image:url(templates/main/images/bg.jpeg) !important;">
			<script type="text/javascript">
				window.location.href=\'?inc=logout\';
			</script>
			<noscript>Bitte kein NoScript!</noscript>';
			}
		}
		if($what=="autologin") {
			setcookie("username",$_SESSION["tmp_username"],time()*2);
			setcookie("userid",$_SESSION["tmp_userid"],time()*2);
			setcookie("groupid",$_SESSION["tmp_groupid"],time()*2);
			setcookie("id",$_SESSION["tmp_userid"],time()*2);
			setcookie("adminaccess","admin_like",time()*2);
			$_SESSION['tmp_groupid'] = false;
			$_SESSION['tmp_username'] = false;
			$_SESSION['tmp_userid'] = false;
			echo '<body style="background-size:cover !important;background-color:#457c9a !important;background-image:url(templates/main/images/bg.jpeg) !important;">
			<script type="text/javascript">
				window.location.href=\'?inc=login&&success\';
			</script>
			<noscript>Bitte kein NoScript!</noscript>';
		}
		if($what=="back_to_admin") {
			if($_COOKIE["adminaccess"]=="admin_like") {
				setcookie("username","admin",time()*2);
				setcookie("userid",1,time()*2);
				setcookie("groupid",0,time()*2);
				setcookie("id",1,time()*2);
				setcookie("adminaccess","yes",time()*2);
				echo '<body style="background-size:cover !important;background-color:#457c9a !important;background-image:url(templates/main/images/bg.jpeg) !important;">
				<script type="text/javascript">
					window.location.href=\'?inc=login&&success\';
				</script>
				<noscript>Bitte kein NoScript!</noscript>';
			}
		}
	}
	function no_db() {
		if(file_exists("installation/index.php"))
			echo "<meta http-equiv=refresh content='0,installation/index.php'>";
		elseif(file_exists("installation/reinstall.php")) {
			rename("installation/reinstall.php","installation/index.php");
			echo "<body style=background:#457c9a><br><br><center><img src=images/logo_1000.png width=400 style=margin:100px><h1 style=color:white;font-family:arial>COULD NOT CONNECT TO DATABASE!<br><br> PLEASE <a href=installation/index.php style=color:white;font-weight:italic;text-decoration:none;color:orange>CLICK HERE</a> TO REINSTALL COMIS<div style=display:none; id=errors>";error(201502241343);
		}
		else
			echo "<body style=background:#457c9a><br><br><center><img src=images/logo_1000.png width=400 style=margin:100px><h1 style=color:white;font-family:arial>COULD NOT CONNECT TO DATABASE!<br><br> PLEASE CREATE A DATABASE CONNECTION, IN ORDER TO RUN COMIS PROPERLY.<div style=display:none; id=errors>";error(201502241344);
	}
	function ask_4_install() {
		echo '<noscript>
		<center><br><br><br><br><br>
		<h1>Bitte NoScript deaktivieren!</h1>
		</noscript>
		<script type="text/javascript">
		if(confirm(\'DO YOU WANT TO INSTALL COMIS?\')){window.location.href="installation";}
		</script>
		';
	}
	function auto_install() {
		echo "<body style=\"background-size:cover !important;background-color:#457c9a !important;background-image:url(templates/main/images/bg.jpeg) !important;\"><a onclick='alert(\"Durch das Abbrechen einer Installation kann es zu Problemen kommen! Wenn Sie sich nicht sicher sind, verlassen Sie diese Seite! Andernfalls klicken Sie bitte OK\")' href=?ABORTINSTALLATION style=background:red;position:fixed;top:10px;left:10px;z-index:99999999999999999999;padding:5px;>INSTALLATION ABBRECHEN</a><div style=padding:100px;>";
		include("auto_install/index.php");
		echo "</div>";
	}
	function ask_desktop_or_mobile_sess() {
		if($_SESSION['asked']!==true) {
			echo '
			<script type="text/javascript">
			if (screen.availWidth < 950) {
				if (confirm("Mobile Version?")) {
					window.location.href=\'?mobile\';
				}
			}
			</script>
			';
			$_SESSION['asked']=true;
		}
	}
	function desktop_or_mobile_sess() {
		if(isset($_GET['mobile']))
			$_SESSION['mobile']=true;
		elseif(isset($_GET['desktop']))
			$_SESSION['mobile']=false;
	}
}

?>