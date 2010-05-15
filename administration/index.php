<?php
include 'controlleur.php';

if (controlleur::droitAdm()==1){
	echo controlleur::acceuil();
}else{
	$url = $_SERVER['REQUEST_URI'];

		$tab =  explode ("administration/",$url);
		$debut_url = $tab[0].'portail/index.php';
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		    <meta http-equiv="refresh" content="0; url='.$debut_url.'">
		    <link rel="stylesheet" type="text/css" href="style.css" />
		</head>
		</html>';

}


?>
