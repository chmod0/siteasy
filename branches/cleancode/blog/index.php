<?php
session_start();
require_once 'blog.php';

if (!Securite::estBanni()){
    Securite::antiDOS();

    if (isset($_GET['site'])&&(!empty($_GET['site'])))
    {	
	$blog = new Blog();
    }
    else
    {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml">
	    <head>
	    <meta http-equiv="refresh" content="0; url=../portail/index.php">
	    <link rel="stylesheet" type="text/css" href="style.css" />
	    </head>
	    </html>';
    }
}
?>
