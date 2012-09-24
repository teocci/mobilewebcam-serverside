<?php
// version 1.01, 09/23/2012;
// common used functions of index.php, gallery.php, mjpeg.php

$root_dir = "archive";

function getparams()
{
  global $start, $len, $fps, $dir, $img;
  
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
  
  return;
};
function navigation_top()
{
    global $dir, $len, $days;    
    
    echo "<p>";
    
    
    $env = basename(getenv("SCRIPT_NAME"));
    list($yy,$mm,$dd) = preg_split('/-/',$dir);

    if ($env != 'index.php')
    {
        echo "<a href='index.php'>Home</a>";
    }
    else
    {
        echo "Home";
    };
    echo "<br>";
    
    echo "Timelapse: ";
    echo "<a href='mjpeg.php?dir=$dir'>".$mm."/".$dd."</a> <br>";
    for($d = 0; $d < sizeof($days); $d++)
    {
        $day = $days[$d];
        list($yy,$mm,$dd) = preg_split('/-/',$day);
        if ($day == $dir && $env != 'index.php' && $env != 'image.php')
        {
    	echo "$mm\/$dd ";
        }
        else
        {
    	echo "<a href='gallery.php?dir=$day"."&len=".$len."'>".$mm."/".$dd."</a> ";
        }
    }
    echo "</p>";
    return;
}
function navigation_bottom()
{
    global $dir, $start, $len, $last, $working_dir, $files; 
    echo "<p>";
    if($start > 0)
    {
        if($start > $len)
        {
            $lstart = ($start - 10 * $len);
            if($lstart < 0)
                $lstart = 0;
            echo "<a href='gallery.php?dir=".$dir."&start=".$lstart."&len=".$len."'>10x back</a>&nbsp;";
        }
    
        $lstart = ($start - $len);
        if($lstart < 0)
            $lstart = 0;
        echo "<a href='gallery.php?dir=".$dir."&start=".$lstart."&len=".$len."'>back</a>&nbsp;";
    }
    
    echo " <-- ".date("Y M d H:i:s", filemtime($working_dir."/".$files[$start]))." --> ";
    
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
	    echo "<a href='gallery.php?dir=".$dir."&start=".$lstart."&len=".$len."'>next</a>&nbsp;";
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
	        echo "<a href='gallery.php?dir=".$dir."&start=".$lstart."&len=".$len."'>10x next</a>&nbsp;";
	    }
        }
    
        $lstart = (sizeof($files) - $len - 1);
        if ($lstart < 0) $lstart = 0;
    
        echo "<a href='gallery.php?dir=".$dir."&start=".$lstart."&len=".$len."'>last</a>&nbsp;";    
    }
    echo "</p>";
    return;
}
function getdirs()
{
    global $root_dir, $days, $fullnamedays, $last_dir, $working_dir, $thumbdir, $dir;
    // $today = date ("Y-m-d", time());
    
    $days = array();
    $pattern = '/\d{4}-\d{2}-\d{2}/';

    $dh = opendir($root_dir);
    while (false !== ($filename = readdir($dh)))
    {
        if(!is_dir($filename))
        {
            if (preg_match($pattern,$filename))
            {
                $days[] = $filename;
                $fullnamedays[] = $root_dir."/".$filename;
            }
        }
    }
    closedir($dh);

    array_multisort(
        $days,
        // array_map( 'filemtime', $fullnamedays ),
        // SORT_NUMERIC,
        SORT_ASC
    );
    $last_dir = end($days);
    if ($dir == "") $dir = $last_dir;
    $working_dir = $root_dir."/$dir";
    $thumbdir = $working_dir."/320x240";    
}
function getfiles()
{
    global $working_dir, $files, $fullnamefiles;
    $dh = opendir($working_dir);
    while (false !== ($filename = readdir($dh)))
    {
        if(!is_dir($filename))
        {
            $files[] = $filename;
            $fullnamefiles[] = $working_dir."/".$filename;
        }
    }
    closedir($dh);
    
    array_multisort(
        array_map( 'filemtime', $fullnamefiles ),
        SORT_NUMERIC,
        SORT_ASC,
        $files
    );    
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
?>