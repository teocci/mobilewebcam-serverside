<?php
// version 1.02, 11/25/2012;
require 'common.php';


getparams();
$status = getdirs();

if ($status == 0)
{
    print "directory \"$archive_dir\" not found<br>";
    exit;
}
getfiles();




?>

<html>
<body>

<?php
navigation_top();

?>

    <?php print "<img src=\"$current\" name=\"refresh\">"; ?>
    <script language="JavaScript" type="text/javascript"> 
    <!--
        <?php print "image = \"$current\""; ?>
        function Reload() { 
        tmp = new Date(); 
        tmp = "?"+tmp.getTime() 
        document.images["refresh"].src = image+tmp 
        setTimeout("Reload()",1000) 
        } 
        Reload(); 
    // --> 
    </script>
      
      
</body>
</html>