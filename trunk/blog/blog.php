<?php
session_start();
require_once 'controller/BlogController.php';
include ('../sites/Securite.php');

if (!Securite::estBanni()){
    Securite::antiDOS();
    /**
     * Classe permettant d'appeler les bonnes m�thode de blogcontroller
     */
    class Blog{

	/**
	 * M�thode appelant la bonne m�thode en fonction de l'action pass�e en param�tre 
	 */
	public function chooseAction($action, $nomSite){
	    $b = new BlogController();
	    switch($action){
	    case 'list' : 
		if($_SESSION['editBlog'] != true)
		    $b->listeAction($nomSite);
		else
		    $b->editListeAction($nomSite);
		break;

		// appel � la fonction d'affichage d�taill� d'un billet
		// si la variable $_SESSION['editBlog'] est � true, on appelle editAction plutot que detailAction 
	    case 'detail' : 
		if($_SESSION['editBlog'] != true)
		    $b->detailAction($nomSite);
		else
		    $b->editDetailAction($nomSite);
		break;

	    case 'cat' : 
		if($_SESSION['editBlog'] != true)
		    $b->categorieAction($nomSite);
		else
		    $b->editCategorieAction($nomSite);
		break;

	    case 'addCom' : 
		$b->ajoutComAction($nomSite);
		$b->detailAction($nomSite);
		break;

	    case 'edit':
		// si la variable de session "connecte" est � true et si le site $nomSite appartient � l'utilisateur $_SESSION['mail']
		require_once("../sites/Site.php");
		$site = Site::findByNomSite($nomSite);
		if($site != null)
		    $mail = $site->getAttr('mail');
		if($_SESSION['connecte'] && $mail == $_SESSION['mail'])
		{
		    // on configure la variable de session 'edit' � true pour les futurs appels � "detail"
		    $_SESSION['editBlog'] = true;
		}
		// on appelle la fonction qui affiche la liste complete des billets
		$b->editListeAction($nomSite);
		break;

	    case 'unedit':
		// on supprime la variable de session 'edit'
		unset($_SESSION['editBlog']);
		echo "<script>window.close()</script>";
		break;

	    case 'new':
		// si la variable de session "connecte" est � true et si le site $nomSite appartient � l'utilisateur $_SESSION['mail']
		require_once("../sites/Site.php");
		$site = Site::findByNomSite($nomSite);
		if($site != null)
		    $mail = $site->getAttr('mail');
		if($_SESSION['connecte'] && $mail == $_SESSION['mail'] && $_SESSION['editBlog'] == true)
		{
		    // on appelle la fonction qui affiche la liste complete des billets
		    $b->newAction($nomSite);
		}
		break;

	    default :
		if($_SESSION['editBlog'] != true)
		    $b->listeAction($nomSite);
		else
		    $b->editListeAction($nomSite);
		break;

	    }
	}
    }

    if (isset($_REQUEST['site'])&&(!empty($_REQUEST['site'])))
    {	
	$blog = new Blog();
	$blog->chooseAction($_REQUEST['action'], $_REQUEST['site']);
    }
    else
    {

	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml">
	    <head>
	    <meta http-equiv="refresh" content="0; url=../portail/index.php">
	    <link rel="stylesheet" type="text/css" href="style.css" />
	    </head>
	    </html>';
    }
    if(isset($_REQUEST['id_billet'])&& isset($_REQUEST['ac'])) {
	$a = new Affichage();
	$billet= Billet::findById($_REQUEST['id_billet']);
	$nom_site = $billet->getAttr('nom_site');
	$menu = BlogController::reloadMenu($nom_site);
	echo $menu;
    }
}
?>
