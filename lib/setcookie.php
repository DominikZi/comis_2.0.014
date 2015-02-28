<?php
//THIS DOCUMENT WILL BE REMOVED IN THE FUTURE
if($_GET['name'] != "userid" && $_GET['name'] != "username") {
	setcookie($_GET['name'], $_GET['value'], time()*2);
}
?>