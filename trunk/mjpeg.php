<?php
//example /mjpeg.php?fps=2&w=320&h=240&convert=200

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

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function get_one_jpeg($filename)
{
    return file_get_contents($filename);
}

ini_set('display_errors', 1);
# Used to separate multipart
$boundary = "my_mjpeg";

# We start with the standard headers. PHP allows us this much
header("Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0");
header("Cache-Control: private");
header("Pragma: no-cache");
header("Expires: -1");
header("Content-type: multipart/x-mixed-replace; boundary=$boundary");

# From here out, we no longer expect to be able to use the header() function
print "--$boundary\n";

# Set implicit flush, and flush all current buffers
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
$frametime = 1000 * 1000 / $fps;

$start = $_GET['start'];
if(!isset($start) || empty($start))
    $start = 0;

$len = $_GET['len'];
if(!isset($len) || empty($len))
    $len = sizeof($files);

$starttime = (int)(microtime_float() * 1000.0 * 1000.0);
$curtime = $starttime;
$delta = 0;

$last = $start + $len - 1;
if($last > sizeof($files))
    $last = sizeof($files) - 1;

# The loop, producing one jpeg frame per iteration
//$count = 0;
for($i = $start; $i <= $last; /*&& $count < 1000; $count++*/)
{
	usleep(1000);

//	if($delta <= $frametime && $frametime - $delta > 0)
//		usleep($frametime - $delta);

//	echo $delta." >=? ".$frametime." <br>";
	if($delta >= $frametime)
	{
	  # Per-image header, note the two new-lines
	  print "Content-type: image/jpeg\n\n";
	
	  # Your function to get one jpeg image
	  print get_one_jpeg($dir."/".$files[$i]);
	
	  # The separator
	  print "--$boundary\n";

	  while($delta >= $frametime)
	  {
		  $delta -= $frametime;
		  $starttime += $frametime; 
		  $i++;
	  }
	}
	
	$curtime = (int)(microtime_float() * 1000.0 * 1000.0);
	$delta = $curtime - $starttime;

//	echo microtime_float().": ".$delta." (".$curtime." - ".$starttime.") <br>";
	
//	if($count == 100)
//	return;
}
?>