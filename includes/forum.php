<style type="text/css">
#forum-menu li {
    color: #333;
    float: left;
    padding: 2px 9px;
    margin: 4px;
    background-color: rgba(255, 255, 255, 0.4);
    border-radius: 4px;
    list-style-type: none;
}
.search-result {
    color: #333;
    margin: 4px;
    margin-left: 6px;
}
.search-result p.left {
    float: left;
}
.search-result p.right {
    float: right;
}
#forum-menu li:hover {
    background-color: rgba(255, 255, 255, 0.6);
}
#forum-menu li:active {
    background-color: rgba(255, 255, 255, 0.8);
}
#forum-menu li.active {
	 color: #DDD;
    background-color: #428BCA;
}
#forum-menu a {
    text-decoration: none;
}
</style>
<?php
$blas = array();
$blas[] = "all_questions";
if(isset($_COOKIE['username'])) {
	$blas[] = "add_questions";
	$blas[] = "my_questions";
}
$blas[] = "search";
echo "<ul id=forum-menu style=margin-left:-43px;margin-top:-20px;>";
foreach($blas as $bla) {
	if($_GET['forum'] == $bla) {
		echo "<a href=?forum=".$bla."><li class=active>".$text[$bla]."</li></a>";
	}
	else {
		echo "<a href=?forum=".$bla."><li>".$text[$bla]."</li></a>";
	}
}
echo "</ul><br>";
if($loginrequired) {
	include("includes/login.php");
}
if($_GET["forum"]=="all_questions") {
	echo "<h2>".$text["all_questions"]."</h2>";
	$result = mysql_query("select * from ".$db_prefix."forum_questions order by timestamp");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			echo "<a href=?forum=question&id=".$row[id]."><li class=search-result>".$row[question]."</li></a>";
			$found=1;
		}
	}
}
if($_GET["forum"]=="my_questions") {
	echo "<h2>".$text["my_questions"]."</h2>";
	$result = mysql_query("select * from ".$db_prefix."forum_questions where userid = '".$_COOKIE["userid"]."' order by timestamp");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			echo "<a href=?forum=question&id=".$row[id]."><li class=search-result>".$row[question]."</li></a>";
			$found=1;
		}
	}
}
if($_GET["forum"]=="question") {
	$result = mysql_query("select * from ".$db_prefix."forum_questions where id = '".$_GET["id"]."'");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			echo "<h2>".$row[question]."</h2>";
			if(file_exists($row[image])) {
				echo "<a href='".$row[image]."'><img src='".$row[image]."' style='max-width:500px;'></a>";
			}
			$found=1;
		}
	}
	if(!$found) {
		echo "<h2>No Question was found!</h2>";
		return;	
	}
	include("answers.php");
}
if($_GET["forum"]=="add_questions") {
	if($_POST["question"]!="") {
		echo "<div class='alert alert-success'>".$text["success_by_action"]."</div>";
		$file = "files/".time()."-".$_FILES['image']['name'];
		if(move_uploaded_file($_FILES['image']['tmp_name'], $file)) {echo "";}
		mysql_query("insert into `".$db_prefix."forum_questions` (`question`,`image`,`userid`) values('".$_POST["question"]."','".$file."','".$_COOKIE["userid"]."')");
	}
	else {
		echo '<form style=font-size:5mm;  enctype="multipart/form-data" action="?forum='.$_GET['forum'].'&id='.$_GET['id'].'" method=post>';
		echo "<h2>".$text["question"]."</h2>";
		echo "<input name='question' size=50 placeholder='".$text["question"]."'>";
		echo "<br><br><label>".$text["add_screenshot"]."</label><br><input id=upload type=file name='image'>";
		echo"
		<br><br>
		<input name='submit' value='".$text[save]."' type='submit'>
		<br>
		</form>";	
	}
}
if($_GET["forum"]=="search") {
	if($_POST["q"]!="") {
		echo "<h2>".$text["results_for_search"]." \"".$_POST['q']."\" :<br></h2>";	
		$result = mysql_query("select * from ".$db_prefix."forum_questions where question like '%".$_POST["q"]."%'");
		if($result) {
			while($row = mysql_fetch_array($result)) {
				echo "<a href=?forum=question&id=".$row[id]."><li class=search-result>".$row[question]."</li></a>";
				$found=1;
			}
		}
		if(!$found) {
			echo "<div style=margin-top:10px;>Zu Ihrer Suche \"".$_POST['q']."\" konnten leider kein Ergebnisse gefunden werden.</div>";	
		}
	}
	else {
		echo "<h2>".$text["search"]."</h2>";
		echo "<form method=post action=?forum=search><input name=q placeholder='".$text[search]."' style='border:0;width:100%;margin-bottom:-15px;'></form>";
	}
}
?>
