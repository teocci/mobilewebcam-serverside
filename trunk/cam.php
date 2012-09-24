<?php
// new file uploaded? copy to $root_dir; create thumbnail;
// version 1.01, 09/23/2012;

$uploadfile = "";
$today = date ("Y-m-d", time());
// all archives are in "root_dir"
$root_dir = "archive";

// every day a new directory archive/2012-09-08, archive/2012-09-09, ...
$working_dir = $root_dir."/$today";

// create thumbnails in directory archive/2012-09-08/320x240"
$thumbdir = $working_dir."/320x240";


if(strlen(basename($_FILES["userfile"]["name"])) > 0)
{
  $uploadfile = basename($_FILES["userfile"]["name"]);
  
  if(!file_exists($root_dir))
  {
    mkdir($root_dir, 0777);
  }
    
  if(!file_exists($working_dir))
    mkdir($working_dir, 0777);
    
  if(!file_exists($thumbdir))
    mkdir($thumbdir, 0777);       
  
  $filename = filemtime($uploadfile).".jpg";
  $archivefile =  $working_dir."/".$filename;
  copy($uploadfile, $archivefile);
  
  if(move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile))
  {
    @chmod($uploadfile,0755);
    echo "Upload Ok!<br>";
  }
  else
    echo "Error create archive-file!<br>";
}

$smallname = $thumbdir."/".$filename;
if(!file_exists($smallname))
{
  $image = @imagecreatefromjpeg($archivefile);
  if($image)
  {
    $new_image = imagecreatetruecolor(320, 240);
    imagecopyresampled($new_image, $image, 0, 0, 0, 0, 320, 240, imagesx($image), imagesy($image));
    imagejpeg($new_image, $smallname);
    echo "Thumbnail Ok!";
  }
  else
  {
    echo "Error create thumbnail!<br>";
  }
}

?>