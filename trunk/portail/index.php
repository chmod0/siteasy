<?php
session_start();
require_once("Vue.php");
require_once("../bdd/Base.php");
include ('../sites/imageController.php');
include ('../sites/Securite.php');

if (!Securite::estBanni()){

    Securite::antiDOS();

    // gestion des actions à effectuer au chargement de l'index si besoin
    $action = $_GET['action'];
    switch($action)
    {
    case "logout":
	$_SESSION['connecte'] = false;
	break;

    case "addSite":
	require_once("../sites/Site.php");
	$site = new Site();
	$site = unserialize($_SESSION['site']);
	if($site != null)
	{
	    $site->insert();
	    unset($_SESSION['site']);
	}
	break;

    case "cancelSite":
	unset($_SESSION['site']);
	break;

    case 'rmimage' :
	if(isset($_SESSION['mail']) && $_SESSION['connecte']&& isset( $_GET['id'])){
	    imageController::supimages($_GET['id']);
	}else{
	    Vue::afficheAccueil($_SESSION['mail']);
	}
	break;

    }
}

if(isset($_SESSION['mail']) && $_SESSION['connecte'] )
{
    require_once("../sites/SiteController.php");
    $mail = $_SESSION['mail'];
    $array_sites = SiteController::userSitesList($mail);
    Vue::afficheProfil($mail, $array_sites);
}
else
{
    Vue::afficheAccueil($_SESSION['mail']);
}

?>
