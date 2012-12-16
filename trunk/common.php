<?php
// version 1.03, 12/03/2012;
// common used functions of index.php, gallery.php, mjpeg.php

// absolute  server path to the script:
$root_dir = getenv('DOCUMENT_ROOT');

// absolute http root dir (containing gallery.php, common.php. mjpeg.php, image.php, index.php) WITH trailing slash.
// the directory with uploaded files may be a subdirectory of this;
// you may have several subdirectories, each subdirectory with one gallery for one camera
// examples: "/" or "/mystartdirectory/" or "/my/start/directory/"
$http_dir = "/";

function getparams()
{
  global $start, $len, $fps, $dir, $img, $cam, $index, $action;
  
/*
Read CGI Parameters and set default values, if not specified in query string;
start:  image number of one day; starts with 0 for first image of the day;
dir:    spezifies the directory; the images of every day are in one directory, format 2012-11-24;
len:    number of thumbnail images shown in gallery, first image is start, last image is start+len-1;
fps:    frames per second for mjpeg script (actually not supported in version 1.02);
img:    image file name, which is shown with image.php;
i:      index of the actual shown image img;
cam:    relative path to http root, where the gallery is located; each cam has its own relative path "cam"
action: commands like "delete", "copy", or...;
*/
  
  if(isset($_GET['start']))
  {
    $start = $_GET['start'];
    if ($start < 0) $start = 0;
  }
  else
  {
    $start = 0;
  };

  if(isset($_GET['dir']))
  {
    $dir = $_GET['dir'];
  }
  else
  {
    $dir = "";
  };

  if(isset($_GET['len']))
  {
    $len = $_GET['len'];
  }
  else
  {
   $len = 12;
  };
  
  if(isset($_GET['fps']))
  {
    $fps = $_GET['fps'];
  }
  else
  {
     $fps = 2;
  };
  if($fps <= 0) {$fps = 0.0001;};

  if(isset($_GET['img']))
  {
    $img = $_GET['img'];
  }
  else
  {
     $img = "";
  };
  
  if(isset($_GET['cam']))
  {
    $cam = $_GET['cam'];
  }
  else
  {
    $cam = "";
  };
  if(isset($_GET['i']))
  {
    $index = $_GET['i'];
    if ($index < 0) {$i = 0;}
  }
  else
  {
    $i = 0;
  };
  if(isset($_GET['action']))
  {
    $action = $_GET['action'];
  }
  else
  {
    $action = "";
  };  
  return;
};
function navigation_top()
{
/*
Navigation on the top of the gallery; select days, homepage, Timelaps of the day...
*/
    global $dir, $len, $days, $root_dir, $cam, $http_dir;    
    
    echo "<p>";
    
    
    $env = basename(getenv("SCRIPT_NAME"));
    list($yy,$mm,$dd) = preg_split('/-/',$dir);

    if ($env != 'index.php')
    {
        echo "<a href='$http_dir"."index.php?cam=$cam'>Home</a>";
    }
    else
    {
        echo "Home";
    };
    echo "<br>";

   
    echo "Timelapse: ";
    echo "<a href='$http_dir"."mjpeg.php?cam=$cam&dir=$dir'>".$mm."/".$dd."</a> <br>";

    for($d = 0; $d < sizeof($days); $d++)
    {
        $day = $days[$d];
        list($yy,$mm,$dd) = preg_split('/-/',$day);
        if ($day == $dir && $env != 'index.php' && $env != 'image.php')
        {
    	echo "$mm/$dd ";
        }
        else
        {
    	echo "<a href='$http_dir"."gallery.php?cam=$cam&dir=$day&len=$len'>".$mm."/".$dd."</a> ";
        }
    }
    echo "</p>";
    return;
}
function navigation_image_top()
{
/*
Navigation on the top of the selected image in image.php; "back" and "next"
*/
    global $dir, $start, $len, $last, $working_dir, $files, $http_dir, $cam, $index;
    if ($index > 0)
    {
        $left = $index - 1;
        $file = $files[$left];
        echo "<a href='image.php?cam=$cam&img=$file&dir=$dir&i=$left'><-- back</a> ";
    }
    if ($index < sizeof($files) - 1)
    {
        $right = $index + 1;
        $file = $files[$right];
        echo " <a href='image.php?cam=$cam&img=$file&dir=$dir&i=$right'>next --></a> ";
    }
}
function navigation_bottom()
{
/*
Navigation on the bottom of the gallery in gallery.php
*/
    global $dir, $start, $len, $last, $working_dir, $files, $http_dir, $cam; 
    echo "<p>";
    if($start > 0)
    {
        if($start > $len)
        {
            $lstart = ($start - 10 * $len);
            if($lstart < 0)
                $lstart = 0;
            echo "<a href='$http_dir"."gallery.php?cam=$cam&dir=$dir&start=$lstart&len=$len'>10x back</a>&nbsp;";
        }
    
        $lstart = ($start - $len);
        if($lstart < 0)
            $lstart = 0;
        echo "<a href='$http_dir"."gallery.php?cam=$cam&dir=$dir&start=$lstart&len=$len'>back</a>&nbsp;";
    }
    
    echo " <-- ".date("Y M d H:i:s", ftime($working_dir."/".$files[$start]))." --> ";
    
    if($last < sizeof($files))
    {
        $lstart = ($start + $len);
        if ($lstart + $len > sizeof($files))
        {
	    $lstart = (sizeof($files) - $len - 1);
	    if ($lstart < 0) $lstart = 0;
	}
	else
	{
	    echo "<a href='$http_dir"."gallery.php?cam=$cam&dir=$dir&start=$lstart&len=$len'>next</a>&nbsp;";
	}
    
        if($last + $len < sizeof($files))
        {
	    $lstart = ($start + 10 * $len);
	    if ($lstart + $len > sizeof($files))
	    {
    		$lstart = (sizeof($files) - $len - 1);
    		if ($lstart < 0) $lstart = 0;
	    }
	    else
	    {
	        echo "<a href='$http_dir"."gallery.php?cam=$cam&dir=$dir&start=$lstart&len=$len'>10x next</a>&nbsp;";
	    }
        }
    
        $lstart = (sizeof($files) - $len - 1);
        if ($lstart < 0) $lstart = 0;
    
        echo "<a href='$http_dir"."gallery.php?cam=$cam&dir=$dir&start=$lstart&len=$len'>last</a>&nbsp;";    
    }
    echo "</p>";
    return;
}
function navigation_image_bottom()
{
/*
Navigation on the bottom of the selected image in image.php; commands like "delete", "copy", ...
*/
    
    global $dir, $start, $len, $last, $working_dir, $files, $http_dir, $cam, $index, $action;
    $file = $files[$index];
    echo "<a href='image.php?cam=$cam&img=$file&dir=$dir&i=$index&action=copy'>copy to archive</a> ";
    echo "<a href='image.php?cam=$cam&img=$file&dir=$dir&i=$index&action=delete'>delete</a> ";  
}
function action()
{
/*
    Execute Commands like "delete", "copy", ...
*/
    global $dir, $start, $len, $last, $working_dir, $thumbdir, $files, $http_dir, $cam, $index, $action, $img, $root_dir;
    if ($action == "delete")
    {
        $smallname = $root_dir.$thumbdir."/".$files[$index];
        $largename = $root_dir.$working_dir."/".$files[$index];
        unlink($smallname);
        unlink($largename);
       if ($index < sizeof($files) - 1)
       {
           $index = $index;
           $img = $files[$index+1];   
       }
       else
       {
           if ($index > 0)
           {
                $index = $index-1;
                $img = $files[$index-1];   
           }
       }
             
    }
    
}
function getdirs()
{
    global $archive_dir, $days, $last_dir, $working_dir, $thumbdir, $dir, $cam, $root_dir, $http_dir, $current, $current_url;
    // $today = date ("Y-m-d", time());
      
    $status = 0;
    $archive_dir = $http_dir.$cam."/archive";
    $archive_dir = str_replace("//", "/", $archive_dir);
    
    $archive_dir_abs = $root_dir.$archive_dir;
    $current = $http_dir.$cam."/current.jpg";
    $current = str_replace("//", "/", $current);

    $days = array();
    $pattern = '/\d{4}-\d{2}-\d{2}/';
    
    if (file_exists($archive_dir_abs))
    {
        $status = 1;
        $dh = opendir($archive_dir_abs);
        while (false !== ($filename = readdir($dh)))
        {
//           if(!is_dir($filename))
//           {
               if (preg_match($pattern,$filename))
               {
                   $days[] = $filename;
                   $fullnamedays[] = $archive_dir_abs."/".$filename;
               }
//           }
        }
        closedir($dh);
    }

    array_multisort(
        $days,
        // array_map( 'filemtime', $fullnamedays ),
        // SORT_NUMERIC,
        SORT_ASC
    );
    $last_dir = end($days);
    if ($dir == "") $dir = $last_dir;
    $working_dir = $archive_dir."/$dir";
    $thumbdir = $working_dir."/320x240";
    return ($status);
}
function getfiles()
{
    global $working_dir, $files, $root_dir;
    $working_dir_abs = $root_dir.$working_dir;
    if (file_exists($working_dir_abs))
    {
        $dh = opendir($working_dir_abs);
        $pattern = '/\d{6,}/';
        while (false !== ($filename = readdir($dh)))
        {
//          if(!is_dir($filename))
//          {
            if (preg_match($pattern,$filename))
                {
                    $files[] = $filename;
                    $fullnamefiles[] = $working_dir_abs."/".$filename;
                }
//          }
        }
        closedir($dh);
    }
    
    if (sizeof($files) > 0)
    {
        array_multisort(
            array_map( 'filemtime', $fullnamefiles ),
            SORT_NUMERIC,
            SORT_ASC,
            $files
            );
    }
}
function rmtree($path)
{
    if (is_dir($path))
    {
        foreach (scandir($path) as $name)
        {
            if (in_array($name, array('.', '..')))
            {
                continue;
            }
            $subpath = $path.DIRECTORY_SEPARATOR.$name;
            rmtree($subpath);
        }
        rmdir($path);
    }
    else
    {
        unlink($path);
    }
}
function ftime($file)
{
    $epoctime = (int)basename($file);
    return($epoctime);
}
?>