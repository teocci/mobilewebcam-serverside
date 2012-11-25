<?php
    $user_dir = getenv('SCRIPT_FILENAME');
    $user_dir = dirname($user_dir);
    $user_dir = substr (strrchr ($user_dir, "/"), 1);

    header ("HTTP/1.1 301 Moved Permanently");
    header ("Location: http://webcam.megsm.de/index.php?cam=$user_dir");
    exit();  
?>