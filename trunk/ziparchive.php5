<?php
// example usage: ziparchive.php?start=14&end=12

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

$start = $_GET['start'];
if(!isset($start) || empty($start))
  $start = 0;

$end = $_GET['end'];
if(!isset($end))
  $end = $start;

if($end > $start + 500)
  $end = $start + 500;

header('content-type: text/html; charset=utf-8');
echo "<html><head><title>zipping archive</title></head><body>";

$zip = new ZipArchive();
$zipfilename = "cam_".date("YMd_His", filemtime($dir."/".$files[$start])).".zip";
echo "Creating $zipfilename ...<p>"; 
if ($zip->open($zipfilename, ZIPARCHIVE::CREATE) === TRUE)
{
	for($count = $start; $count <= $end; $count++)
	{
	    $fullnamefile = $dir."/".$files[$count];
	    echo $fullnamefile." -> ".$files[$count]." (".date("YMd_H:i:s", filemtime($fullnamefile)).")<br>";
	    $zip->addFile($fullnamefile, $files[$count]);
	}
    $zip->close();
    echo "==> Finished!";

	for($count = $start; $count <= $end; $count++)
	{
	    $fullnamefile = $dir."/".$files[$count];
	    unlink($fullnamefile);
	}
}
else
{
    echo "==| Failed!";
}

echo "</body></html>";

?>