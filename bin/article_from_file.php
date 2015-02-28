<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IMPORT</title>
</head>
<body>
<?php
include("../db.php");
//die($_FILES["file"]["type"]);
if(!is_admin())die("<body style=background:url(../templates/main/images/bg.jpeg);background-size:cover;color:white;font-family:helvetica><br><br><h1 style=text-align:center;>".$text["admin_require"].".</h1><h2 style=text-align:center;><a href=../?inc=login&username=admin style=color:lightblue;text-decoration:none;>&gt; &gt; ".$text["login"]."  &lt; &lt;</a></h2>");
if (!isset($_FILES["file"])) {
	error(201502281606);
}
else {
	if(strstr($_FILES["file"]["name"],".odt")) {
		require_once("odt_import.php");
		if (($_FILES["file"]["size"] < 1e6))
		  {
		  if ($_FILES["file"]["error"] > 0)
		    {
		    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
		    }
		  else
		    {
			$time = microtime(true);
			$db->query("insert into ".$db_prefix."articles(timestamp,title,code,pageid) values('".time()."','".ucfirst(str_replace(".odt","",$_FILES["file"]["name"]))."','".odt2html($_FILES["file"]["tmp_name"])."','".$_POST["pageid"]."')");
		    }
		  }
		else
		{
			echo "Invalid file";
		}
	}
	elseif(strstr($_FILES["file"]["type"],"image/")) {
		smkdir("../files/images");
		$end=explode("/",$_FILES["file"]["type"]);
		$filename=uniqid().rand(0,9e9).".".$end[1];
		if(move_uploaded_file($_FILES["file"]["tmp_name"],"../files/images/".$filename)) {
			$code="<img src=\"files/images/".$filename."\" style=\"max-width:95%\" class=\"article-img article-auto-generated\">";
			$title=explode(".",$_FILES["file"]["name"]);
			$db->query("insert into ".$db_prefix."articles(timestamp,title,code,pageid) values('".time()."','".ucfirst($title[0])."','".$code."','".$_POST["pageid"]."')");
		}
		else error(201502281654);
//		die("hi");
	}
	elseif(strstr($_FILES["file"]["type"],"audio/")) {
		smkdir("../files/audio");
		$end=explode("/",$_FILES["file"]["type"]);
		$filename=uniqid().rand(0,9e9).".".$end[1];
		if(move_uploaded_file($_FILES["file"]["tmp_name"],"../files/audio/".$filename)) {
			$code="<audio controls src=\"files/audio/".$filename."\" style=\"max-width:95%\" class=\"article-audio article-auto-generated\"></audio>";
			$title=explode(".",$_FILES["file"]["name"]);
			$db->query("insert into ".$db_prefix."articles(timestamp,title,code,pageid) values('".time()."','".ucfirst($title[0])."','".$code."','".$_POST["pageid"]."')");
		}
		else error(201502281656);
//		die("hi");
	}
	elseif(strstr($_FILES["file"]["name"],".css")) {
		smkdir("../files/stylesheets");
		move_uploaded_file($_FILES["file"]["tmp_name"], "../files/stylesheets/".time().uniqid().".css");
	}
	elseif(strstr($_FILES["file"]["type"],"video/")) {
		smkdir("../files/video");
		$end=explode("/",$_FILES["file"]["type"]);
		$filename=uniqid().rand(0,9e9).".".$end[1];
		if(move_uploaded_file($_FILES["file"]["tmp_name"],"../files/video/".$filename)) {
			$code="<video controls src=\"files/video/".$filename."\" style=\"max-width:95%\" class=\"article-video article-auto-generated\"></video>";
			$title=explode(".",$_FILES["file"]["name"]);
			$db->query("insert into ".$db_prefix."articles(timestamp,title,code,pageid) values('".time()."','".ucfirst($title[0])."','".$code."','".$_POST["pageid"]."')");
		}
		else error(201502281657);
//		die("hi");
	}
	elseif(strstr($_FILES["file"]["type"],"application/pdf")) {
		smkdir("../files/upload");
		$end=explode("/",$_FILES["file"]["type"]);
		$filename=uniqid().rand(0,9e9).".".$end[1];
		if(move_uploaded_file($_FILES["file"]["tmp_name"],"../files/upload/".$filename)) {
			$code='<object data="files/upload/'.$filename.'" type="'.$_FILES["file"]["type"].'" width="100%" height="800"><p>It appears you don"t have an html 5 object plugin for this browser. <a href=files/upload/'.$filename.'>click here</a> to view the file.</p></object>';
			$title=explode(".",$_FILES["file"]["name"]);
			$db->query("insert into ".$db_prefix."articles(timestamp,title,code,pageid) values('".time()."','".ucfirst($title[0])."','".$code."','".$_POST["pageid"]."')");
		}
		else error(201502281657);
//		die("hi");
	}
/*	<object data="myfile.pdf" type="application/pdf" width="100%" height="100%">
 
  <p>It appears you don't have a PDF plugin for this browser.
  No biggie... you can <a href="myfile.pdf">click here to
  download the PDF file.</a></p>
  
</object>*/
	else {
		smkdir("../files/tmp");
		$filename=uniqid().rand(0,9e9);
		if(move_uploaded_file($_FILES["file"]["tmp_name"],"../files/tmp/".$filename)) {
			$code=str_replace("'","\&\#39;",str_replace("<","\&lt;",file_get_contents("../files/tmp/".$filename)));
			$title=explode(".",$_FILES["file"]["name"]);
			$db->query("insert into ".$db_prefix."articles(timestamp,title,code,pageid) values('".time()."','".ucfirst($title[0])."','".$code."','".$_POST["pageid"]."')");
			unlink("../files/tmp/".$filename);
		}
		else error(201502281653);
	}
	if($_POST["pageid"]=="" && $db->insert_id!=0)
		echo "<script>window.location.href='../?article=".$db->insert_id."';</script>";
	elseif($_POST["pageid"]!="")
		echo "<script>window.location.href='../?page=".$_POST['pageid']."';</script>";
	else
		echo "<script>window.location.href='../';</script>";
}
?>
</body>
</html>