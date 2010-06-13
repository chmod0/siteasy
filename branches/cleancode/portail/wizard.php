<?php
session_start();
require_once("vueWizard.php");
require_once("../sites/Site.php");
require_once("../bdd/Base.php");

$pageAAfficher = $_GET['goto'];
$etape = $_GET['etape'];
$mail = $_SESSION['mail'];

// test si etape est passé en paramètre GET
// si c'est le cas, on vient d'une étape du wizard et on a modifié des données
// Sauvegarde des paramètres POST dans la BDD
if(isset($etape))
{
    switch ($etape)
    {
    case 1:
	$nom_site = $_POST['nom_site'];
	$titre_site = $_POST['titre_site'];
	$desc_site = $_POST['desc_site'];
	$mots_cle = $_POST['mots_cle'];
	$categ_site = $_POST['categ_site'];
	if( ! isset($_SESSION['site']))
	{
	    $site = new Site();
	    $site->setAttr('mail', $mail);
	    $site->setAttr('id_modele', 0);
	    $site->setAttr('id_design', 0);
	}
	else
	{
	    $site = new Site();
	    $site = unserialize($_SESSION['site']);
	}
	$site->setAttr('nom_site', $nom_site);
	$site->setAttr('titre_site', $titre_site);
	$site->setAttr('desc_site', $desc_site);
	$site->setAttr('categ_site', $categ_site);
	$site->setAttr('mots_cle', $mots_cle);

	$_SESSION['site'] = serialize($site);
	break;
    
    case 2:
	$id_modele = $_POST['id_modele'];

	$site = unserialize($_SESSION['site']);
	if(isset($site))
	{
	    $site->setAttr('id_modele', $id_modele);
	      
	    $_SESSION['site'] = serialize($site);
	}
	break;

    case 3:
		$id_design = $_POST['id_design'];
		$site = unserialize($_SESSION['site']);
		if(isset($site)){
			$site->setAttr('id_design', $id_design);
			$_SESSION['site'] = serialize($site);

		}
	
	break;
	
	default:
	break;

    }
}
vueWizard::afficheWizard($mail, $pageAAfficher);

?>
