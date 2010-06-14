<?php
session_start();

require_once("../bdd/Base.php");
require_once("Site.php");
class SiteController
{
    function userSitesList($mail)
    {
	$action = $_SESSION['action'];
	$mail = $_SESSION['mail'];

	$array_sites = Site::findByUser($mail);
	return $array_sites;
    }

    function choseAction()
    {
	$action = $_REQUEST['action'];
	switch($action)
	{
	case 'detailsSite':
	    require_once("../design/design.php");
	    require_once("../modele/Modele.php");

	    $nom_site = $_POST['nomSite'];
	    //création d'un objet Site qui contient toutes les données d'un site
	    $site = Site::findByNomSite($nom_site);

	    // Modification de l'objet site pour avoir les libelles du modele et du design 
	    $modid = $site->getAttr('id_modele');
	    $mod = Modele::findById($modid);
	    $modlib = $mod->getAttr('libelle_modele');

	    $desid = $site->getAttr('id_design');
	    $des = design::findById($desid);
	    $deslib = $des->getAttr('libelle_design');

	    $site->setAttr('id_modele', $modlib);
	    $site->setAttr('id_design', $deslib);
	    // on passe l'objet en array pour pouvoir le transmettre en JSON
	    $array_site = $site->toArray();

	    // utilisation de la bibliothèque PHP pour créer un objet JSON
	    require_once("JSON.php");
	    $objetJSON = new Services_JSON();
	    $resultatJSON = $objetJSON->encode($array_site);

	    echo $resultatJSON;
	    break;

	case 'modifDetails':
	    $nom_site = $_REQUEST['nom_site'];
	    $titre_site = $_REQUEST['titre_site'];
	    echo $titre_site;
	    $categ_site = $_REQUEST['categ_site'];
	    echo $categ_site;
	    $mots_cle = $_REQUEST['mots_cle'];
	    echo $mots_cle;
	    $description_site = $_REQUEST['desc_site'];
	    echo $description_site;
	    $design_site = $_REQUEST['design_site'];
	    echo $design_site;

	    $site = Site::findByNomSite($nom_site);
	    if($site != null)
	    {
		$site->setAttr('titre_site', $titre_site);
		$site->setAttr('categ_site', $categ_site);
		$site->setAttr('mots_cle', $mots_cle);
		$site->setAttr('desc_site', $description_site);
		$site->setAttr('id_design', $design_site);

		$site->save();
	    }
	    break;

	case 'nomSiteExists':
	    $nom_site = $_POST['nomSite'];
	    //création d'un objet Site qui contient toutes les données d'un site
	    $site = Site::findByNomSite($nom_site);

	    if ($site == null){
		echo "false";
	    }else{
		echo "true";
	    }
	    break;

	case 'supprSite':
	    $nom_site = $_POST['nomSite'];
	    $site = Site::findByNomSite($nom_site);
	    if($site != null)
	    {
		$modele = $site->getAttr("id_modele");
		if($modele == 1)
		{
		    // suppression de tous les billets liés au site
		    require_once("../blog/model/Billet.php");
		    $billets = Billet::findByNomSite($nom_site);
		    if($billets != null)
		    {
			foreach($billets as $b)
			{
			    $b->delete();
			}
		    }
		}
		else if($modele == 2)
		{
		    require_once("../page/Page.php");
		    $pages = Page::findBySite($nom_site);
		    if($pages != null)
		    {
			foreach($pages as $p)
			{
			    $p->delete();
			}
		    }
		}
		$site->delete();
		echo "Site supprimé";
	    }
	    else
	    {
		echo "Site non supprimé";
	    }
	    break;
	}
    }
}

$sc = new SiteController();
$sc->choseAction();
?>
