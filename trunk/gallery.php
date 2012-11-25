<?php
// version 1.02, 11/25/2012;
// example usage: gallery.php?start=14&len=12
require 'common.php';

getparams();
$status = getdirs();

if ($status == 0)
{
    print "directory \"$archive_dir\" not found<br>";
    exit;
}


getfiles();


header('content-type: text/html; charset=utf-8');
echo "<html><head><title>".date("Y M d H:i:s", ftime($working_dir."/".$files[$start]))."</title></head><body>";

navigation_top();
navigation_bottom();

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
    $smallname_abs = $root_dir."/".$smallname;
    if (file_exists($smallname_abs))
    {
	echo "<a href='image.php?cam=$cam&img=$filename&dir=$dir&i=$count'><img src='".$smallname."' title='".date("H:i:s", ftime($working_dir."/".$filename))."'></a> ";
    }
}
echo "</p>";

navigation_bottom();

echo "</body></html>";

?>