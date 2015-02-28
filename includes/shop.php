<style type="text/css">
#shop-menu li {
    color: #333;
    float: left;
    padding: 2px 9px;
    margin: 4px;
    background-color: rgba(255, 255, 255, 0.4);
    border-radius: 4px;
    list-style-type: none;
}
#shop-categories-list li {
    color: #333;
    padding: 2px 9px;
    margin: 4px;
    background-color: rgba(255, 255, 255, 0.4);
    border-radius: 4px;
    list-style-type: none;
}
#shop-categories-list a {
	text-decoration: none;
}
#shop-categories-list li:hover {
    background-color: rgba(255, 255, 255, 0.6);
}
#shop-menu li:hover {
    background-color: rgba(255, 255, 255, 0.6);
}
#shop-menu li:active {
    background-color: rgba(255, 255, 255, 0.8);
}
#shop-menu li.active {
	 color: #DDD;
    background-color: #428BCA;
}
#shop-menu a {
    text-decoration: none;
}
.shop-item {
	display: inline-table;
	width: 94%;
	background: hsla(0,0%,100%,.4);
	padding: 0 3%;
	margin: 2%;
	margin-left: 0;
	border-radius: 10px;
}
.shop-item.shop-item-detail {
	background: none;
	width:100%;
	margin-left:-20px; 
}
.shop-item img {
	background: hsla(0,0%,100%,.2);
	border-radius: 5px;
	border: 1px solid skyblue;
	opacity: .9;
	width: 99.3%;
}
.shop-item h3 {
	font-variant: small-caps;
	margin: .5em;
	font-size: 1.3em;
}
.shop-item-detail h2 {
	margin: 0;
	font-size: 40px;
	margin-top: -10px;
}
.shop-item .item-options {
	margin-top: 10px;
}
.shop-item .item-options span:hover , .item-options span:active {
    background: rgba(255, 255, 255, 0.5) !important;
    cursor: default;
}
.shop-item p {
	margin-top: 50px;
	padding: 3px;
	margin-bottom: 5px;
	clear: both;
}
.categorie-shop-item {
	margin-left: 1px;
}
</style>
<?php
$blas[] ="all_shop_items";
$blas[] ="shop_categories";
$blas[] ="search";
$result = mysql_query("select * from ".$db_prefix."shop_useritems where userid = '".$_COOKIE["userid"]."'");
if($result) {
	while($row = mysql_fetch_array($result)) {
		$result2 = mysql_query("select * from ".$db_prefix."shop_items where id = '".$row[itemid]."'");
		if($result2) {
			while($row2 = mysql_fetch_array($result2)) {
				$intmyarticles++;
			}
		}
	}
}
if(isset($_GET["remove_from_my_shop_items"])) {
	$intmyarticles--;
}
if(isset($_GET["add_to_my_shop_items"])) {
	$intmyarticles++;
}
if($intmyarticles < 1) {
	$intmyarticles=0;
}
if(isset($_COOKIE['username'])) {
$blas = array("all_shop_items","shop_categories","my_shop_items","search");
}
echo "<ul id=shop-menu style=margin-left:-43px;margin-top:-20px;>";
foreach($blas as $bla) {
	if($bla == "my_shop_items") {
		if($_GET['shop'] == $bla) {
			echo "<a href=?shop=".$bla."><li class=active>".$text[$bla]." (".$intmyarticles.")</li></a>";
		}
		else {
			echo "<a href=?shop=".$bla."><li>".$text[$bla]." (".$intmyarticles.")</li></a>";
		}
	}
	else {
		if($_GET['shop'] == $bla) {
			echo "<a href=?shop=".$bla."><li class=active>".$text[$bla]."</li></a>";
		}
		else {
			echo "<a href=?shop=".$bla."><li>".$text[$bla]."</li></a>";
		}
	}
}
echo "</ul>";
echo "<br>";
if(isset($_GET["remove_from_my_shop_items"])) {
	mysql_query("delete from ".$db_prefix."shop_useritems where `itemid`='".$_GET['id']."' and `userid`='".$_COOKIE['userid']."'");
}
if($_GET["shop"] == "item") {
	if(isset($_GET["add_to_my_shop_items"])) {
		mysql_query("insert into ".$db_prefix."shop_useritems values(NULL,'".$_COOKIE['userid']."','".$_GET['id']."',NULL)");
	}
	$result = mysql_query("select * from ".$db_prefix."shop_items where id = '".$_GET['id']."'");
	if($result) {
		while($row = mysql_fetch_array($result)) {
				echo "<div class='shop-item-detail shop-item'><h2>".$row[title]."</h2><img width=100% src='".$row[image]."'><div class=item-options><span style=float:left; class=btn>".$row[price]." &euro;</span>";
				$result2 = mysql_query("select * from ".$db_prefix."shop_useritems where itemid = '".$_GET["id"]."' and userid = '".$_COOKIE["userid"]."'");
				if($result2) {
					while($row2 = mysql_fetch_array($result2)) {
						$is_already_in_my_shop_items = true;
					}
				}
				if($is_already_in_my_shop_items) {
					echo "<a href=?shop=item&id=".$row[id]."&remove_from_my_shop_items><button style=float:right; class=btn>".$text["remove_from_my_shop_items"]."</button></a>";
				}
				else {
					echo "<a href=?shop=item&id=".$row[id]."&add_to_my_shop_items><button style=float:right; class=btn>".$text["add_to_my_shop_items"]."</button></a>";
				}
				echo "</div><p>".$row[description]."</p></div>";
		}	
	}
}
elseif($_GET["shop"]=="shop_categories") {
	$result = mysql_query("select * from ".$db_prefix."shop_categories where id = '".$_GET["id"]."'");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			echo "<h2 style='margin: 5px;'>".$text["categorie"].": ".$row[name]."</h2>";
		}
	}
	if(isset($_GET['id'])) {
		$result = mysql_query("select * from ".$db_prefix."shop_items where categorieid = '".$_GET["id"]."'");
		if($result) {
			while($row = mysql_fetch_array($result)) {
				echo "<div class='shop-item categorie-shop-item'><h3>".$row[title]."</h3><img width=100% src='".$row[image]."'><div class=item-options><span style=float:left; class=btn>".$row[price]." &euro;</span><a href=?shop=item&id=".$row[id]."><button style=float:right; class=btn>".$text["item_more_details"]."</button></a></div><p>".$row[description]."</p></div>";
			}
		}
	}
	else {
		echo "<h2 style='margin: 5px;'>".$text["categories"]."</h2>";
		echo "<div id=shop-categories-list>";
		$result = mysql_query("select * from ".$db_prefix."shop_categories");
		if($result) {
			while($row = mysql_fetch_array($result)) {
				echo "<a href=?shop=shop_categories&id=".$row[id]."><li>".$row[name]."</li></a>";
			}
		}
		echo "</div>";
	}
}
elseif($_GET["shop"]=="search") {
	echo "<form method=post action=?shop=search><input name=q placeholder='".$text[search]."' style='border:0;width:100%;margin-bottom:-15px;'>
<!--
<input type=\"range\" min=\"0\" max=\"50\" value=\"0\" step=\"5\" onchange=\"showValue(this.value)\" />
<span id=\"range\">0</span>
<script type=\"text/javascript\">
function showValue(newValue)
{
	document.getElementById(\"range\").innerHTML=newValue;
}
</script>
-->
</form>
";
if($_POST["q"]!="") {
	echo "<h2 style=margin:0;margin-top:25px;>".$text["results_for_search"]." \"".$_POST['q']."\" :<br></h2>";	
	$result = mysql_query("select * from ".$db_prefix."shop_items where title like '%".$_POST["q"]."%'");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			echo "<div class='shop-item categorie-shop-item'><h3>".$row[title]."</h3><img width=100% src='".$row[image]."'><div class=item-options><span style=float:left; class=btn>".$row[price]." &euro;</span><a href=?shop=item&id=".$row[id]."><button style=float:right; class=btn>".$text["item_more_details"]."</button></a></div><p>".$row[description]."</p></div>";
			$found=1;
		}
	}
	if(!$found) {
		echo "<div style=margin-top:10px;>Zu Ihrer Suche \"".$_POST['q']."\" konnten leider kein Ergebnisse gefunden werden.</div>";	
	}
}
}
elseif($_GET["shop"]=="my_shop_items") {
	$result = mysql_query("select * from ".$db_prefix."shop_useritems where userid = '".$_COOKIE["userid"]."'");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			$result2 = mysql_query("select * from ".$db_prefix."shop_items where id = '".$row[itemid]."'");
			if($result2) {
				while($row2 = mysql_fetch_array($result2)) {
					echo "<div class=shop-item><h3>".$row2[title]."</h3><img width=100% src='".$row2[image]."'><div class=item-options><span style=float:left; class=btn>".$row2[price]." &euro;</span><a href=?shop=item&id=".$row2[id]."><button style=float:right; class=btn>".$text["item_more_details"]."</button></a></div><p>".$row2[description]."</p></div>";
				}
			}
			else {
				echo "Keine Favouriten gefunden! (Fehlercode:2)";
			}
		}
	}
	else {
		echo "Keine Favouriten gefunden!";
	}
}
elseif($_GET['shop'] == "home" | $_GET['shop'] == "all_shop_items") {
	$i = -1;
	echo '<table style="margin-left:-7px;"><tr><td style="width:49.5%;vertical-align:top;">';
	$result = mysql_query("select * from ".$db_prefix."shop_items");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			if($i == -1) {
				echo "<div class=shop-item><h3>".$row[title]."</h3><img width=100% src='".$row[image]."'><div class=item-options><span style=float:left; class=btn>".$row[price]." &euro;</span><a href=?shop=item&id=".$row[id]."><button style=float:right; class=btn>".$text["item_more_details"]."</button></a></div><p>".$row[description]."</p></div>";
			}
			$i=$i*-1;
		}
	}
	echo '</td><td style="width:1%"></td><td style="width:49.5%;vertical-align:top;">';
	$i = 1;
	$result = mysql_query("select * from ".$db_prefix."shop_items");
	if($result) {
		while($row = mysql_fetch_array($result)) {
			if($i == -1) {
				echo "<div class=shop-item><h3>".$row[title]."</h3><img width=100% src='".$row[image]."'><div class=item-options><span style=float:left; class=btn>".$row[price]." &euro;</span><a href=?shop=item&id=".$row[id]."><button style=float:right; class=btn>".$text["item_more_details"]."</button></a></div><p>".$row[description]."</p></div>";
			}
			$i=$i*-1;
		}
	}
	echo '</td>
	</tr>
	</table>';
}
?>
