<?php
	$result = $db->query("SELECT * FROM `".$db_prefix."articles` WHERE (`code` LIKE '%".$_GET['q']."%' OR `title` LIKE '%".$_GET['q']."%') and public = 'yes'");
	if($result->num_rows) {
		echo "<div style=\"margin:5px;font-size:16px;\">".$text["before_search"]." ".mysql_result(mysql_query("select count(*) FROM `".$db_prefix."articles` WHERE (`code` LIKE '%".$_GET['q']."%' OR `title` LIKE '%".$_GET['q']."%') and public = 'yes'"),0)." ".$text["between_search"]."  \"".$_GET['q']."\" ".$text["after_search"]."</div>";
		while($row = $result->fetch_object()) {
			$code=$row->code;
			$code_ex=explode("<",$code);
			$code=$code_ex[0];
			foreach($code_ex as $code_part) {
				$code_ex2=explode(">",$code_part);
				$code=$code.$code_ex2[1];
			}
			if(strlen($code)>140)
				$code=substr($code, 0, 130)."<span style=opacity:.7;>".substr($code, 130, 2)."</span><span style=opacity:.4;>".substr($code, 132, 2)."</span><span style=opacity:.2;>".substr($code, 134, 2)."</span> . . .";
			echo "<a style=text-decoration:none; href='";
		if($rewrite)
			echo "article";
		else
			echo "?article=";
		echo $row->id."'><div class=comment>".$row->title."<br>".$code." </div></a>";	
			$found=true;
		}
	}
	else
		echo "Fehler";	
	if(!$found)
		echo "Zu Ihrer Suche \"".$_GET['q']."\" konnten leider kein Ergebnisse gefunden werden.";	
?>