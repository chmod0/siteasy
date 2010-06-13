<?php

include('../bdd/Base.php');



$val = $_POST['nom'];



echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		    <title>EasyWeb, le portail simplifié de création de sites Web</title>
		    <link rel="stylesheet" type="text/css" href="css/style.css" />
		</head>
		<body>';

echo "
$val
<a href=\"#\" onClick=\"window.open('insertion_image.php','Fiche','toolbar=no,status=no,width=450 ,height=700,scrollbars=yes,location=no,resize=yes,menubar=yes')\" >Ajouter une nouvelle image</a>
";

echo ' </body></html>';



?>
