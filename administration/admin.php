<?php
include_once ('controlleur.php');

if (!Securite::estBanni()){
    Securite::antiDOS();
    if (controlleur::droitAdm()==1){

	if(isset($_GET['action'])){
	    $action = $_GET['action'];
	    switch($action){
	    case 'lstsit' :
		if (isset($_GET['mail'])){
		    controlleur::listeSiteUser($_GET['mail']);
		}
		break;

	    case 'lstallsit' :
		controlleur::listeSite();
		break;

	    case 'lstban' :
		controlleur::listeBanUser();
		break;
		
	    case 'doban' :
		if (isset($_POST['ip'])){
		    controlleur::bannirIP($_POST['ip']);
		    controlleur::listeBanUser();
		}
		break;

	    case 'rmsit' :
		if (isset($_GET['nom'])){
		    controlleur::supprimerSite($_GET['nom']);
		    controlleur::listeSite();
		}

		break;

	    case 'rmusr' :
		if (isset($_GET['mail'])){
		    controlleur::supprimerUser($_GET['mail']);
		    controlleur::acceuil();
		}
		break;

	    case 'doadm' :
		if (isset($_GET['mail'])){
		    controlleur::donneDroit($_GET['mail']);
		    controlleur::acceuil();
		}
		break;

	    case 'deban' :
		if (isset($_GET['ip'])){
		    controlleur::deBannirIP($_GET['ip']);
		    controlleur::listeBanUser();
		}
		break;
		
	    case 'stat' :
		controlleur::stat();
		break;

	    case 'lcs' :
		if (isset($_GET['nom']))
		    controlleur::listeContenuSite($_GET['nom']);
		break;

	    case 'lstimB' :
		if (isset($_GET['deb'])&&isset($_GET['fin']))
		    controlleur::images ($_GET['deb'],$_GET['fin']);
		break;

	    case 'lstim' :
		controlleur::images ();
		break;

	    case 'rmim' :
		if (isset($_GET['id'])){
		    controlleur::supimages($_GET['id']);
		    controlleur::images ();
		}
		break;

	    case 'imusr' :
		if (isset($_GET['mail'])){
		    controlleur::imagesUser($_GET['mail']);

		}
		break;

	    case 'imusB' :
		if (isset($_GET['mail'])&&isset($_GET['deb'])&&isset($_GET['fin'])){
		    controlleur::imagesUser($_GET['mail'],$_GET['deb'],$_GET['fin']);

		}
		break;

	    default :
		// acceuil
		controlleur::acceuil();
		break;
	    }
	}

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
}
?>
