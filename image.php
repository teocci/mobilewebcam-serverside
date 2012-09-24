<?php
// version 1.01, 09/23/2012;
require 'common.php';

getparams();
getdirs();
getfiles();

header('content-type: text/html; charset=utf-8');
echo "<html><head><title>".date("Y M d H:i:s", filemtime($working_dir."/".$img))."</title></head><body>";

navigation_top();
echo "<p>";
echo date("Y M d H:i:s", filemtime($working_dir."/".$img));
echo "</p>";

echo "<p>";
echo "<img src='".$working_dir."/".$img."' title='".date("Y M d H:i:s", filemtime($working_dir."/".$img))."'>";
echo "</p>";

echo "</body></html>";

?>