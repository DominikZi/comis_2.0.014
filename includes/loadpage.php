<?php
include("../db.php");
include("../language/".pref("language").".php");
include_once("../lib/global_functions.php");
if($_GET["article"]!="") {
$found=false;
$result = $db->query("select * from `".$db_prefix."articles` where id = '".$_GET['article']."' order by timestamp desc limit 1");
if($result->num_rows) {
	while($row = $result->fetch_object()) {
		$arr_article=$row;
	}
	if($arr_article) {
		if($arr_article->public!="yes" && is_admin())
			$not_public=true;
		else
			$not_public=false;
		if($not_public)
			echo "<div class=\"alert alert-danger\">".$text["article_not_public"]."</div>";
		if($arr_article->public=="yes" || is_admin()) {
			echo "<h2>".$arr_article->title." ";
			show_editicons($arr_article->id);
			echo "</h2><div class=pre>".$arr_article->code."</div><br class=clear>";
			if($arr_article->comments == "yes" && pref('pages_enabled')!="no" && pref('comments')!="no")
				include("comments.php");
			if(pref('pages_enabled')!="no")
				echo "<hr class=clear><div class=articlefooter> ".$text["editor"]." : ".$arr_article->editor." -  ".$text["date"]." : ".date("d.m.y H:i",$arr_article->timestamp)." -  ".$text["views"]." : ".$arr_article->views." </div>";
			$found=true;
		}
	}
}
else
	echo "<h2>".$text["no_article"]."</h2>";
	}

if($_GET["page"]!="") {
								$found=false;
								$result = $db->query("select * from ".$db_prefix."articles where pageid = '".$_GET['page']."' order by timestamp desc");
								if($result) {
									while($row = $result->fetch_object()) {
										if($found) echo "<hr class=clear>";
										$not_public=($row->public!="yes" && is_admin())?true:false;
										if($not_public)
											echo "<div class=\"alert alert-danger\">".$text["article_not_public"]."</div>";
										if($row->public=="yes" || is_admin()) {
											echo "<h2>".$row->title;
											show_editicons($row->id,$_GET["page"]);
											echo "</h2>
											<div class=pre>".$row->code."</div>
											<br class=clear>
											<a onmousedown=fadeOut(); href='";
											echo ($rewrite)?"article":".?article=";
											echo $row->id."' class='btn-comis'>
											&gt;&gt; ".$text["view_article"]." &lt;&lt;
											</a>
											<br><br>";	
											$found=true;
										}
									}
								}
								if(!$found)
								echo "<h2> ".$text["no_page"]." </h2>";
	}
?>