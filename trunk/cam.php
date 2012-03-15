<?php
// new file uploaded?
$uploadfile = "";
if(strlen(basename($_FILES["userfile"]["name"])) > 0)
{
  $uploadfile = basename($_FILES["userfile"]["name"]);
  
  if(!file_exists("archive/"))
    mkdir("archive", 0755);
  copy("current.jpg", "./archive/".filemtime("current.jpg").".jpg");

  if(move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile))
  {
    @chmod($uploadfile,0755);
    echo "Ok!";
  }
  else
    echo "Error copying!";
}
else
  echo $_POST["userfile"]; // some plain text!
?>