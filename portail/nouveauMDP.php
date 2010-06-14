<?php
require_once("Vue.php");
$mail = $_GET['mail'];
$id = $_GET['id'];
echo Vue::recuperation($mail,$id);
?>
