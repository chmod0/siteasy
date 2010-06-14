<?php
include ("recuperation.php");
include ("../portail/Vue.php");

$id = $_GET['id'];
$mail = $_GET['mail'];

$recup = recuperation::findById($id);
if($recup!=null){
    header("Location: ../portail/nouveauMDP.php?id=$id&mail=$mail");
}else{

    header('Location: ../portail/index.php');

}

?>
