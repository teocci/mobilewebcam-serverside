<?php
// version 1.01, 09/23/2012;
// example usage: galler.php?start=14&len=12
require 'common.php';

getparams();
getdirs();
getfiles();


header('content-type: text/html; charset=utf-8');
echo "<html><head><title>".date("Y M d H:i:s", filemtime($working_dir."/".$files[$start]))."</title></head><body>";

navigation_top();

// Gallery
echo "<p>";
if($start > sizeof($files))
    $start = sizeof($files)-1;
$last = $start + $len;
if($last > sizeof($files))
    $last = sizeof($files)-1;
for($count = $start; $count < $last; $count++)
{
    $filename = $files[$count];
    $smallname = $thumbdir."/".$filename;
    if (file_exists($smallname))
    {
	echo "<a href='image.php?img=".$filename."&dir=".$dir."'><img src='".$smallname."' title='".date("H:i:s", filemtime($working_dir."/".$filename))."'></a> ";
    }
}
echo "</p>";

navigation_bottom();

echo "</body></html>";

?>