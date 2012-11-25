<?php
// version 1.00, 09/08/2012;
// call /mjpeg.php?fps=2&start=0&len=50
require 'common.php';

getparams();
getdirs();
getfiles();

$boundary = "my_mjpeg";

header("Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Cache-Control: private");
header("Pragma: no-cache");
header("Expires: -1");
header("Content-type: multipart/x-mixed-replace; boundary=$boundary");

print "--$boundary\n";

@ini_set('implicit_flush', 1);
for ($i = 0; $i < ob_get_level(); $i++)
    ob_end_flush();
ob_implicit_flush(1);

$waittime = 1000 * 1000 / $fps;

$max = sizeof($files);
if ($max > 288)
{
  $max = 288;
};

$last = $start + $max - 1;

if($last > sizeof($files))
{
    $last = sizeof($files) - 1;
};

for($i = $start; $i <= $last;)
{
    $tmp = file_extension($thumbdir."/".$files[$i]);
    if($tmp == "jpg")
    {
      print "Content-type: image/jpeg\n\n";
      print file_get_contents($thumbdir."/".$files[$i]);
      print "--$boundary\n";
    }
    else
    {
      //print "Content-type: text/html\n\n";
      //print "--$boundary\n";
    }
    ;
    $i++;
    usleep($waittime);
};


function file_extension($filename)
{
    $path_info = pathinfo($filename);
    if (isset($path_info['extension']))
    {
        $tmp = $path_info['extension'];
    }
    else
    {
         $tmp = "";
    };
    return $tmp;
};
?>