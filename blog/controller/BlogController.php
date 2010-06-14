<?php
session_start();
require_once './view/Affichage.php';
require_once './view/AffichageEditeur.php';
require_once '../sites/Site.php';

/**
 * Controleur permettant de g�n�rer les affichages
 */
class BlogController {

    /**
     * M�thode r�alisant l'affichage de tous les billets
     */
    public function listeAction($nomSite) {
	$a = new Affichage();
	$billets = Billet::findByNomSite($nomSite);
	$categories = Categorie::findBySite($nomSite);
	$site = Site::findByNomSite($nomSite);
	if($site != null)
	    $titre = $site->getAttr('titre_site');

	$a->affichePage($a->listeBillets($billets),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets));
    }

    /**
     * M�thode r�alisant l'affichage des d�tails d'un billet
     */
    public function detailAction($nomSite) {
	$a = new Affichage();
	$billets = Billet::findByNomSite($nomSite);
	$categories = Categorie::findBySite($nomSite);
	$site = Site::findByNomSite($nomSite);
	if($site != null)
	    $titre = $site->getAttr('titre_site');

	if (isset($_GET['id']) && $_GET['id'] != '' && (Billet::findByID($_GET['id']) != null)) {
	    $billet = Billet::findById($_GET['id']);
	    $id = $billet->getAttr('id_billet');
	    $commentaires = Commentaire::findByBillet($id);
	    $a->affichePage($a->unBillet($billet,$commentaires),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets));
	}
	else {
	    echo '<div class=error><b>BILLET INEXISTANT</b></div>';
	    $a->affichePage($a->listeBillets($billets),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets));
	}
    }

    /**
     * M�thode r�alisant l'affichage de tous les billets d'une cat�gorie
     */
    public function categorieAction($nomSite) {
	$a = new Affichage();
	$billets = Billet::findByNomSite($nomSite);
	$categories = Categorie::findBySite($nomSite);
	$site = Site::findByNomSite($nomSite);
	$titre = $site->getAttr('titre_site');

	if (isset($_GET['id']) && $_GET['id'] != '') {
	    $categorie = Categorie::findById($_GET['id']);
	    $billet = Billet::findByCat($_GET['id']);
	    $a->affichePage($a->listeBillets($billet,$categorie),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets));
	}else {
	    echo '<div class=error><b>CATEGORIE INEXISTANTE</b></div>';
	    $a->affichePage($a->listeBillets($billets),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets));
	}
    }

    /**
     * M�thode r�alisant l'affichage des d�tails d'un billet
     */
    public function ajoutComAction($nomSite) {
	if (($_REQUEST['auteur'] != '') && ($_REQUEST['titre'] != '') && ($_REQUEST['contenu'] != '')) {
	    $com = new Commentaire();
	    $com->setAttr('titre_com',$_REQUEST['titre']);
	    $com->setAttr('auteur_com',$_REQUEST['auteur']);
	    $com->setAttr('mail_auteur_com',$_REQUEST['mail']);
	    $com->setAttr('contenu_com',$_REQUEST['contenu']);
	    $com->setAttr('id_billet',$_REQUEST['id_billet']);
	    $com->insert();
	    echo '<div class=popup><b>COMMENTAIRE AJOUTE</b></div>';
	}
    }

    /**
     * M�thode r�alisant l'affichage de tous les billets avec la barre de l'�diteur
     */
    public function editListeAction($nomSite) {
	$ae = new AffichageEditeur();
	$a = new Affichage();
	$billets = Billet::findByNomSite($nomSite);
	$categories = Categorie::findBySite($nomSite);
	$site = Site::findByNomSite($nomSite);
	if($site != null)
	    $titre = $site->getAttr('titre_site');

	$ae->affichePage($a->listeBillets($billets),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets), $_GET['id']);
    }

    /**
     * M�thode r�alisant l'affichage de l'�diteur d'un billet
     */
    public function editDetailAction($nomSite) {
	// affichage sp�cifique � l'�diteur
	$aed = new AffichageEditeur();
	// affichage commun au blog
	$a = new Affichage();
	$billets = Billet::findByNomSite($nomSite);
	$categories = Categorie::findBySite($nomSite);
	$site = Site::findByNomSite($nomSite);
	if($site != null)
	    $titre = $site->getAttr('titre_site');

	if (isset($_GET['id']) && $_GET['id'] != '' && (Billet::findByID($_GET['id']) != null)) {
	    $billet = Billet::findById($_GET['id']);
	    $cat = $billet->getAttr("id_categ");
	    $aed->affichePage($aed->unBillet($billet, $categories, $cat),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets), $_GET['id']);
	}
	else {
	    echo '<div class=error><b>BILLET NOT FOUND</b></div>';
	    $a->affichePage($a->listeBillets($billets),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets));
	}
    }

    public function editCategorieAction($nomSite) {
	$a = new Affichage();
	$ae = new AffichageEditeur();
	$billets = Billet::findByNomSite($nomSite);
	$categories = Categorie::findBySite($nomSite);;
	$site = Site::findByNomSite($nomSite);
	$titre = $site->getAttr('nom_site');

	if (isset($_GET['id']) && $_GET['id'] != '') {
	    $categorie = Categorie::findById($_GET['id']);
	    $billet = Billet::findByCat($_GET['id']);
	    $ae->affichePage($ae->listeBillets($billet,$categorie),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets), $_GET['id']);
	}else {
	    echo '<div class=error><b>CATEGORIE NOT FOUND</b></div>';
	    $a->affichePage($a->listeBillets($billets),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets));
	}
    }

    public function newAction($nomSite) {
	// affichage spécifique à l'éditeur
	$aed = new AffichageEditeur();
	// affichage commun au blog
	$a = new Affichage();
	$billets = Billet::findByNomSite($nomSite);
	$categories = Categorie::findBySite($nomSite);

	$site = Site::findByNomSite($nomSite);
	if($site != null)
	    $titre = $site->getAttr('titre_site');

	$billet =new Billet();
	$billet->setAttr("contenu_billet", "Cliquez ici pour modifier le contenu du billet");
	$billet->setAttr("titre_billet", "Cliquez ici pour modifier le titre du billet");
	$billet->setAttr("id_categ", 0);
	$billet->setAttr("image", null);
	$billet->insert();
	$id= $billet->getAttr('id_billet');
	$aed->affichePage($aed->unBillet($billet,$categories, null),$a->listeTitresCategories($categories),$a->listeTitresBillets($billets), $id);

    }

    public function reloadMenu($nom_site) {
	$a = new Affichage();
	$billets = Billet::findByNomSite($nom_site);
	$menu = '<h3>Billets</h3>'.$a->listeTitresBillets($billets);
	echo $menu;
    }
}
?>
