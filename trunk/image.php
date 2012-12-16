<?php
// version 1.03, 12/03/2012;
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
echo "<html><head><title>".date("Y M d H:i:s", ftime($working_dir."/".$img))."</title></head><body>";

navigation_top();
navigation_image_top();
action();

echo "<p>";
echo date("Y M d H:i:s", ftime($working_dir."/".$img));
echo "</p>";

echo "<p>";
echo "<img src='".$working_dir."/".$img."' title='".date("Y M d H:i:s", ftime($working_dir."/".$img))."'>";
echo "</p>";
navigation_image_bottom();

echo "</body></html>";

?>