<?php
session_start();
include_once ('controller.php');
include ('../sites/Securite.php');
if (!Securite::estBanni()){
    Securite::antiDOS();
    if(isset($_REQUEST['action'])){
	$action = $_REQUEST['action'];
	switch($action){
	case 'detail' :
	    if (isset($_GET['num']))
		controller::pageNum($_GET['num']);
	    break;

	case 'detail_titre' :
	    if (isset($_GET['titre']) && isset($_GET['site']))
		controller::pageTitre($_GET['titre'], $_GET['site']);
	    break;

	case 'Sup' :
	    if (isset($_REQUEST['num']) )
		controller::Supression(null,$_REQUEST['num']);
	    break;

	case 'ediTitre' :
	    if (isset($_POST['num']) )
		controller::EditionTitrePage($_POST['num'],$_POST['titre']);
	    break;

	case 'ediContenu' :
	    if (isset($_POST['num']) )
		controller::EditionContenuPage($_POST['num'],$_POST['contenu']);
	    break;

	case 'insertTitre':
	    if ( isset($_REQUEST['site']))
	    {
		$id = controller::newInsertPageTitre();
		echo $id;
	    }
	    break;

	case 'insertContenu':
	    if ( isset($_REQUEST['site']))
	    {
		$id = controller::newInsertPageContenu();
		echo $id;
	    }
	    break;

	case 'new' :
	    if ( isset($_GET['site']))
		controller::newPage($_GET['site']);
	    break;

	case 'Designe' :
	    if ( isset($_GET['site']))
		controller::ChangeModele($_GET['site']);
	    controller::defaut($_GET['site']);
	    break;

	case 'newL' :
	    if (isset($_GET['cible']) && isset($_GET['source'])){
		controller::ajoutLien($_GET['cible'],$_GET['source']);
		controller::pageNum($_GET['source']);
	    }
	    break;

	case 'lien' :
	    if (isset($_GET['num']) ){
		controller::lien($_GET['num']);
	    }
	    break;

	case 'SupL' :
	    if (isset($_GET['id']) ){
		controller::supprimerLien($_GET['id']);
	    }
	    break;

	case 'lienExt' :
	    if (isset($_GET['num']) ){
		controller::lienExterne($_GET['num'],$_POST['url']);
	    }
	    break;

	case 'formim' :
	    if (isset($_GET['num'])){
		controller::formim($_GET['num']);
	    }
	    break;

	case 'codemim' :
	    if (isset($_GET['num'])&&isset($_POST['im'])&&isset($_POST['haut'])&&isset($_POST['larg'])){
		controller::codemim($_GET['num'],$_POST['im'],$_POST['haut'],$_POST['larg']);
	    }
	    break;

	case 'editMode':
	    // si la variable de session "connecte" est à true et si le site $nomSite appartient à l'utilisateur $_SESSION['mail']
	    require_once("../sites/Site.php");
	    $nomSite = $_GET['site'];
	    $site = Site::findByNomSite($nomSite);
	    if($site != null)
		$mail = $site->getAttr('mail');
	    if($_SESSION['connecte'] && $mail == $_SESSION['mail'])
	    {
		// on configure la variable de session 'edit' à true pour les futurs appels à "detail"
		$_SESSION['editPage'] = true;
	    }
	    // on appelle la fonction qui affiche la liste complete des billets
	    controller::editMode($nomSite);
	    break;

	case 'uneditMode':
	    // on supprime la variable de session 'edit'
	    unset($_SESSION['editPage']);
	    echo "<script>window.close()</script>";
	    break;

	default :
	    if($_SESSION['editPage'] != true)
		controller::defaut($_GET['site']);
	    else
		controller::editMode($_GET['site']);
	    break;	

	}
    }
}
?>
