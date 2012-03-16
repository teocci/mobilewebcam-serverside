<?php
// call /mjpeg.php?fps=2&w=320&h=240&convert=200

$convert = $_GET['convert'];
if(isset($convert) && !empty($convert))
{
    $width = $_GET['w'];
    if(!isset($width) || empty($width))
      $width = 320;
    $height = $_GET['w'];
    if(!isset($height) || empty($height))
      $height = 240;

    $dir = $width."x".$height;

    if(!file_exists($dir."/") && $convert > 0)
        mkdir($dir, 0755);
    else if(!file_exists($dir."/") && $convert == 0)
        $dir = "archive";

    if($convert > 0)
    {
        $count = 0;
        if ( $dh = opendir ( "archive/" ) )
        {
            while ( false !== ( $dat = readdir ( $dh ) ) )
            {
                if ( $dat != "." && $dat != ".." && $count < $convert)
                {
                    $smallname = $dir."/".$dat;
                    if(!file_exists($smallname))
                    {
                        $image = imagecreatefromjpeg("archive/".$dat);
                        if($image)
                        {
                            $new_image = imagecreatetruecolor($width, $height);
                            imagecopyresampled($new_image, $image, 0, 0, 0, 0, 320, 240, imagesx($image), imagesy($image));
                            imagejpeg($new_image, $smallname);
                            $count++;
                        }
                    }
                }
            }
            closedir ( $dh );
        }
    }
}
else
{
    $dir = "archive";
}

ini_set('display_errors', 1);
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

$dh = opendir($dir);
while (false !== ($filename = readdir($dh)))
{
  if(!is_dir($filename))
  {
    $files[] = $filename;
    $fullnamefiles[] = $dir."/".$filename;
  }
}
closedir($dh);

array_multisort(
    array_map( 'filemtime', $fullnamefiles ),
    SORT_NUMERIC,
    SORT_ASC,
    $files
);

$fps = $_GET['fps'];
if(!isset($fps) || empty($fps))
  $fps = 2;
if($fps <= 0)
  $fps = 0.0001;
$waittime = 1000 * 1000 / $fps;

$start = $_GET['start'];
if(!isset($start) || empty($start))
    $start = 0;

$len = $_GET['len'];
if(!isset($len) || empty($len))
    $len = sizeof($files);

$last = $start + $len - 1;
if($last > sizeof($files))
    $last = sizeof($files) - 1;

for($i = $start; $i <= $last;)
{
	print "Content-type: image/jpeg\n\n";
	
	print file_get_contents($dir."/".$files[$i]);
	
	print "--$boundary\n";
  
  $i++;

//	usleep($waittime);
}
?>