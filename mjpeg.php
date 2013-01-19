<?php
// version 1.04, 12/01/2013;
// usage: http://www.opensmartcam.com/mjpeg.php?cam=MyWebcamDirectory&dir=2013-01-19

// comment out the following 4 lines, if you do not use a database and no registration script:
session_start();
session_regenerate_id(true);
require 'register/includes/functions.php';
require 'register/includes/config.php';

// read cgi parameters, scan directories, prepare a list of pictures:
require 'common.php';
getparams();
getdirs();
getfiles();
?>

<html>
<head>
<title>OpenSmartCam</title>
<link rel="stylesheet" href="/css/osc1.css" type="text/css" />
<script src="js/mjpeg.js" type="text/javascript"></script>
</head>
<body>

<div id='wrapper'>
<?php
    include("inc/navi.php");
?>

<div id='content'>
    
<p><a href='index.php<?php print "?cam=$cam&dir=";?>'>Index</a></p>


<img name="foto">  

<SCRIPT LANGUAGE="JavaScript"> 
var Pic = new Array();  
var Pic= ["<?php echo join("\", \"", $fileslist); ?>"];
var t; 
var j = 0; 
var p = Pic.length;
var intervall = 500;
var inc = 1;
var inc2 = 0;
var stop = 0;

var preLoad = new Array(); 
//load all images:
for (i = 0; i < p; i++)
{ 
    preLoad[i] = new Image(); 
    preLoad[i].src = Pic[i]; 
}
document.images['foto'].src = preLoad[0].src;
</SCRIPT>

<form method = "post" name="TimelapsNavi" >
<input type="button" id="StartStop" name="Start" value="Start" onClick="start(1)";>
<input type="button" id="PauseContinue" name="Pause" value="Pause ||" onClick="pause(1)";>
<input type="button" id="ForwardRewind" name="Change" value="Rewind <<" onClick="rewind(1)";>

</form>
<span id="fooBar">&nbsp;</span>
</div> <!--id=content-->
</div> <!--id=wrapper-->

<?php
    include("inc/footer.php");
?>

</body>
</html>
