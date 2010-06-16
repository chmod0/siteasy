<?php

require_once("controller/EditionController.php");

$id = $_REQUEST['id'];
$action = $_REQUEST['action'];

switch($action)
{
case "modifTitre":

    EditionController::modificationTitreBillet();
    echo "Titre sauvegardé";
    break;
case "modifContenu":
    EditionController::modificationContenuBillet();
    echo "Contenu sauvegardé";
    break;
case "supprBillet":
    EditionController::suppressionBillet();
    echo "Billet supprimé";
    break;
case "insertTitre":
    $retour = EditionController::ajoutTitreBillet();
    echo $retour;
    break;
case "insertContenu":
    $retour = EditionController::ajoutContenuBillet();
    echo $retour;
    break;
case "insertCategorie":
    $retour = EditionController::ajoutCategorie();
    echo $retour;
    break;
case "supprCategorie":
    $retour = EditionController::suppressionCategorie();
    echo $retour;
    break;
}
?>
