<?php
session_start();
include '../sites/Securite.php';
include_once 'controller.php';

if (!Securite::estBanni()){
	Securite::antiDOS();
	$bool =true;
	if (isset($_REQUEST['site'])&&(!empty($_REQUEST['site']))){
		if (controller::existeSite($_REQUEST['site'])){
		    if(! ($_SESSION['editPage'] && $_SESSION['connecte']) )
		    {
			controller::defaut($_REQUEST['site']);
		    }
		    else
		    {
			controller::editMode($_REQUEST['site']);
		    }
		    $bool=false;

		}
	}
	if ($bool){
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
