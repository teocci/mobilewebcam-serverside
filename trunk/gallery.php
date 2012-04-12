<?php
// example usage: galler.php?start=14&len=12&img_width=320&img_height=240

$dir = "archive";

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

$count = 0;
foreach($files as $filename)
{
    // find start of day
    $curday = date ("d M Y", filemtime($dir."/".$filename));
    if(sizeof($days) == 0 || $curday != end($days))
    {
        $days[] = $curday;
        $daystarts[] = $count;
    }

    $count++;
}

$start = $_GET['start'];
if(!isset($start) || empty($start))
  $start = 0;

$len = $_GET['len'];
if(!isset($len) || empty($len))
  $len = 12;

$img_width = $_GET['img_width'];
if(!isset($img_width) || empty($img_width))
  $img_width = 240;

$img_height = $_GET['img_height'];
if(!isset($img_height) || empty($img_height))
  $img_height = 180;

$thumbdir = $img_width."x".$img_height;
if(!file_exists($thumbdir."/"))
	mkdir($thumbdir, 0755);

header('content-type: text/html; charset=utf-8');
echo "<html><head><title>".date("Y M d H:i:s", filemtime($dir."/".$files[$start]))."</title></head><body>";

for($d = 0; $d < sizeof($days); $d++)
{
    $day_date = $days[$d];
    echo "<a href='gallery.php?start=".$daystarts[$d]."&len=".$len."&img_width=".$img_width."&img_height=".$img_height."'>".$day_date."</a> ";
}

echo "<p>";

$last = $start + $len;
if($last > sizeof($files))
    $last = sizeof($files);

for($count = $start; $count < $last; $count++)
{
    $filename = $files[$count];

    $smallname = $thumbdir."/".$filename;
    if(!file_exists($smallname))
    {
        $image = @imagecreatefromjpeg("archive/".$filename);
        if($image)
        {
            $new_image = imagecreatetruecolor($img_width, $img_height);
            imagecopyresampled($new_image, $image, 0, 0, 0, 0, $img_width, $img_height, imagesx($image), imagesy($image));
            imagejpeg($new_image, $smallname);
        }
    }

    echo "<a href='".$dir."/".$filename."'><img src='".$smallname."' width='".$img_width."' height='".$img_height."' title='".date("H:i:s", filemtime($dir."/".$filename))."'></a> ";
}

echo "<p>";

if($start > 0)
{
    if($start > $len)
    {
        $lstart = ($start - 10 * $len);
        if($lstart < 0)
            $lstart = 0;
        echo "<a href='gallery.php?start=".$lstart."&len=".$len."&img_width=".$img_width."&img_height=".$img_height."'>10x BACK</a> ";
    }

    $lstart = ($start - $len);
    if($lstart < 0)
        $lstart = 0;
    echo "<a href='gallery.php?start=".$lstart."&len=".$len."&img_width=".$img_width."&img_height=".$img_height."'>BACK</a> ";
}

echo " <<<===--- ".date("Y M d H:i:s", filemtime($dir."/".$files[$start]))." ---===>>> ";

if($last < sizeof($files))
{
    $lstart = ($start + $len);
    echo "<a href='gallery.php?start=".$lstart."&len=".$len."&img_width=".$img_width."&img_height=".$img_height."'>NEXT</a> ";

    if($last + $len < sizeof($files))
    {
      $lstart = ($start + 10 * $len);
      echo "<a href='gallery.php?start=".$lstart."&len=".$len."&img_width=".$img_width."&img_height=".$img_height."'>10x NEXT</a>";
    }

    $lstart = (sizeof($files) - $len);
    echo "<a href='gallery.php?start=".$lstart."&len=".$len."&img_width=".$img_width."&img_height=".$img_height."'>LAST</a>";
}

echo "<hr>Timelapse: ";

for($d = 0; $d < sizeof($days); $d++)
{
    $day_date = $days[$d];
    echo "<a href='mjpeg.php?start=".$daystarts[$d]."'>".$day_date."</a> ";
}

echo "</body></html>";

?>