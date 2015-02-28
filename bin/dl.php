<?php
$file = "../files/tmp/comis_export_".$_GET["uniqid"].".zip";
if(!file_exists($file)) {echo "<script>alert('This file has been downloaded already!');</script>";
echo "<meta http-equiv=refresh content=0,../admin/?action=11&Wiederherstellung><script>window.location.href='../admin/?action=11&Wiederherstellung';</script>";return;}
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"".$file."\"");
header('Content-disposition: filename="comis_exported.zip"');
readfile($file);
unlink($file);
?>