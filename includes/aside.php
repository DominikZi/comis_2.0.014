<?php
echo "<h4>".$text["latest_articles"]."</h4>";
$result = $db->query("select * from ".$db_prefix."articles where public = 'yes' and title != '' order by timestamp desc limit 6");
if($result) {
	while($row = $result->fetch_object()) {
		echo "<a onmousedown=loadarticle('".$row->id."');preventDefault(); onmouseup=fadeOut(); href='";
		if($rewrite)
			echo "article";
		else
			echo "?article=";
		echo $row->id."'><li>".$row->title."</li></a>";	
	}
}
echo "<h4>".$text["popular_articles"]."</h4>";
$result = $db->query("select * from ".$db_prefix."articles where public = 'yes' and title != '' order by views desc limit 6");
if($result) {
	while($row = $result->fetch_object()) {
		echo "<a onmousedown=loadarticle('".$row->id."');preventDefault(); onmouseup=fadeOut(); href='";
		if($rewrite)
			echo "article";
		else
			echo "?article=";
		echo $row->id."'><li>".$row->title."</li></a>";	
	}
}

echo "<br><br><br>";
echo "<b>".ucfirst(pref('user_name'));
if(@$_COOKIE['username']=="admin") {
	if(pref('user_name')=="")
		echo "<span style=\"opacity:.5;\">Ihr Name</span>";
	echo " <a class='changeopacity icon' onmousedown=fadeOut(); href=admin/?action=9&dropdown=1&view_next=true&active_field=name><img src=templates/main/images/edit.png alt='' style=margin-left:10px;height:23px;margin-top:-7px;margin-bottom:-2px;></a>";
}
$email = "<a onmouseup=fadeOut(); href=mailto:".pref("user_email").">".pref("user_email")."</a>";
echo "</b><br>".$email;
?>