<?php
include("../lib/global_functions.php");
if(!is_admin()) return;
$zip = new ZipArchive();
substr($string, $start, $length = null);
$id=substr(md5(rand(0,99999999999999999999999999999999999999999999)),rand(0,10),rand(5,10)).uniqid();
if(!is_dir("../files/tmp")) mkdir("../files/tmp");
$zip->open('../files/tmp/comis_export_'.$id.'.zip',  ZipArchive::CREATE);
$zip->addFile("../backup/tmp.bckp","comis_exported.bckp");
$zip->addFile("../includes/mainpage.php","mainpage.php");
$zip->addFile("../etc/main.conf","main.conf");
$zip->addFile("../etc/inst.conf","inst.conf");
$zip->addFile("../etc/db.conf","db.conf");
$zip->addFile("../etc/about.conf","about.conf");
$zip->close();
unlink("../backup/tmp.bckp");
echo '<p style=font-size:30px;font-family:helvetica,arial;color:#444;padding:30px>Please wait ...</p>
<script type="text/javascript">
window.setTimeout(function(){window.location.href="../admin/?action=11";},1500);
</script>
<body background=http://localhost/comis_1.2.2/templates/main/images/bg.jpeg style=background-size:cover>
<script type="text/javascript">
window.location.href="dl.php?uniqid='.$id.'";
</script>';?>
