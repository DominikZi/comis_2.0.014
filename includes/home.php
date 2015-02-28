<h2><?php show("welcome_msg"); 
if($_COOKIE['username'] == "admin") {
	echo " <a class='changeopacity icon' onmousedown=fadeOut(); href=admin/?action=9><img src=templates/main/images/edit.png alt='' style=height:27px;margin-top:-10px;margin-bottom:-2px;></a>";
}		
?></h2>
<?php
if(pref("welcome_img_yn")!="no") {
	if(file_exists(pref("welcome_img")))
		echo '<img class=thumb src="'.pref("welcome_img").'" width="100%"><br><img src=templates/main/images/shadow_default.png style="margin-top:-4px;" width="100%">';
	else
		echo '<img class=thumb src="images/welcome_img.jpeg" width="100%"><br><img src=templates/main/images/shadow_mirror.png style="margin-top:-4px;" width="100%">';
}
?>
<?php
$result = $db->query("select * from ".$db_prefix."articles where public = 'yes' order by timestamp desc");
if($result->num_rows) {
	while($row = $result->fetch_object()) {
		if($hr) echo "<br><hr>";
		echo "<h2>".$row->title;
		if(is_admin()) show_editicons($row->id);
		echo "</h2><p style=width:100%;white-space:pre-wrap;>".$row->code."</p>";
		if(pref("homepage")!="article") {
			echo "<a onmousedown=loadarticle(".$row->id."); href='";
			if($rewrite)
				echo "article";
			else
				echo "?article=";
			echo $row->id."' class='btn-comis'>&gt;&gt; ".$text["view_article"]." &lt;&lt;</a><br>";
			$hr=true;
		}
	}
}
?>	