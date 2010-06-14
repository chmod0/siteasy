<?php
require_once '../bdd/Base.php';
require_once 'model/Billet.php';
require_once 'model/Categorie.php';
require_once 'model/Commentaire.php';
require_once '../sites/Site.php';
require_once '../design/design.php';
require_once '../sites/image.php';
/**
 * Classe permettant l'affichage des diff�rents composants du blog
 */
class Affichage{
    /**
     * M�thode pour afficher toute une page,
     * Elle prend en param�tre le bloc du centre, le menu de gauche et celui de droite
     */
    public function affichePage($content, $menuleft, $menuright) 
    {
	$site = Site::findByNomSite($_REQUEST['site']);
	if($site != null)
	{
	    $id_design = $site->getAttr('id_design');
	    $design = Design::findById($id_design);
	    $path_design = $design->getAttr('path_design');
	    $titre = $site->getAttr('titre_site');
	}
	else
	{
	    $titre = "Blog inexistant";
	    $design = Design::findById(1);
	    $path_design = $design->getAttr('path_design');
	}

	echo '
	    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	    <title>' . $titre . '</title>
	    <link href="../'.$path_design.'style.css" rel="stylesheet" type="text/css" media="screen"/>
	    <link rel="stylesheet" href="../image/css/lightbox.css" type="text/css"  />
	    <script type="text/javascript" src="../image/js/prototype.js"></script>
<script type="text/javascript" src="../image/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="../image/js/lightbox.js"></script>

</head>

<body>
<div id="entete">
<h1><a href="blog.php?site=' . $_GET['site'] . '">' . $titre . '</a></h1>
</div>

<div id="gauche">
<h3>Cat&eacute;gories</h3>
'.$menuleft.'
</div>

<div id="droite">
<h3>Billets</h3>
'.$menuright.'
</div>


<div id="centre">
'.$content.'
<br/>
</div>

<div id="basdepage"><a href="../portail/index.php">Portail de cr&eacute;ation EasyWeb</a></div>
</body>
</html>
';
    }

    /**
     * M�thode permettant d'afficher le billet pass� en param�tre
     */
    public function unBillet($billet,$commentaires){

	$idIm= $billet->getAttr('image');
	if ($idIm!=0){
	    $image=image::findById($idIm);
	    $titre = $image->getAttr('nom_image');
	    $nom_dur = $idIm;
	    $nom_dur .= '.'.substr($titre, -3);
	    $url_image = '../image/image/'.$nom_dur;



	    $im .= "&nbsp;<a href=\"$url_image\"  rel=\"lightbox[roadtrip]\"><img src=\"$url_image\"  height=\"200\" width=\"200\" /></a>&nbsp;";



	}
	$ret = '<div class="titre">' .$billet->getAttr('titre_billet'). '</div><br/>
	    <center> '.$im.'</center>
	    <div class="contenu">' .$billet->getAttr('contenu_billet'). 
	    '</div><br/><br/><div style="text-align:right"><em>Post&eacute; le '.substr($billet->getAttr('date_billet'),8,2). '/' .substr($billet->getAttr('date_billet'),5,2). '/' .substr($billet->getAttr('date_billet'),0,4).' &agrave; '.substr($billet->getAttr('date_billet'),10,20).'</em></div><br/>';
	$ret .= '<div style="color:#90c7e1 ;border-bottom:1px solid #b8d2d2"> </div><br/>';

	foreach ($commentaires as $com){
	    $ret .= 'Par <em><a href="mailto:'.$com->getAttr('mail_auteur_com').'">'. $com->getAttr('auteur_com') .' </em> - </a>' .$com->getAttr('titre_com') .' le '. $com->getAttr('date_com');
	    $ret .= '<div style="text-align:justify;background-color:#323f47; -webkit-border-radius:5px; padding:5px; margin:5px;">' .$com->getAttr('contenu_com') . '</div>';
	}

	$ret .= '<br/><form method="post" action="blog.php?action=addCom&id=' . $billet->getAttr('id_billet') . '&site=' . $_REQUEST['site'] . '">
	    <fieldset>
	    <legend>Poster un commentaire</legend>
	    <table>
	    <input type="hidden" name="id_billet" value="'.$billet->getAttr('id_billet').'"/>
	    <tr><td>Auteur : </td><td><input type="text" name="auteur"/></td></tr>

	    <tr><td>Adresse email : </td><td><input type="text" name="mail"/></td></tr>

	    <tr><td>Titre : </td><td><input type="text" name="titre"/></td></tr>
	    </table>
	    <br/><textarea rows="10" cols="60" name="contenu" id="contenu_commentaire" onfocus=\'document.getElementById("contenu_commentaire").value="";\'>Tapez votre commentaire ici</textarea>						

	    <p><input type="submit" name="add_categorie" value="Poster le commentaire"></p>
	    </fieldset>
	    </form><br/>
	    ';
	return $ret;
    }

    /**
     * M�thode permettant d'afficher une liste de billets pass�e en param�tre
     */
    public function listeBillets($billets, $cat=null){
	$ret = '';
	if($billets != null)
	    $site = $billets[0]->getAttr('nom_site');
	if ($cat != null){
	    $ret .= '<div id="namecat">' . $cat->getAttr('titre_categ') . '</div>
		<div id="descat">' . $cat->getAttr('libelle_categ') .'</div>
		<div style="clear:both"></div>';
	}
	if (count($billets) == 0){
	    $ret .= '<br/><br/><div style="text-align:center"><b>Aucun billet trouv&eacute; dans cette cat&eacute;gorie</b></div><br/>';
	}else{
	    foreach ($billets as $billet){

		$suite = '';
		if (strlen($billet->getAttr('contenu_billet')) > 200){

		    $suite = '...<a href="blog.php?action=detail&id=' .$billet->getAttr('id_billet'). '&site=' . $site . '"><strong> Lire la suite</strong></a>';
		}

		$ret .= '<div class="titre"><a href="blog.php?action=detail&id=' .$billet->getAttr('id_billet'). '&site=' .  $site . '">' .$billet->getAttr('titre_billet'). '</a></div><br/>
		    ' .substr($billet->getAttr('contenu_billet'),0,200).$suite. '
		    <br/><br/><div style="text-align:right"><em>Post&eacute; le ' .substr($billet->getAttr('date_billet'),8,2). '/' .substr($billet->getAttr('date_billet'),5,2). '/' .substr($billet->getAttr('date_billet'),0,4).' &agrave; '.substr($billet->getAttr('date_billet'),10,20).'</em><br/>
		    <a href="blog.php?action=detail&id=' .$billet->getAttr('id_billet'). '&site=' . $site . '">Commentaire(s)</a></div>';
	    }
	}

	return $ret;
    }

    /**
     * M�thode permettant d'afficher la liste des cat�gories
     */
    public function listeTitresCategories($categories){
	$ret =' ';
	$nb =count($categories);
	if ($nb == 0){
	    $ret .= "Pas de categorie";
	}
	else
	{

	    foreach($categories as $cat)
	    {
		$suite = '';
		$titre = $cat->getAttr('titre_categ');
		if (strlen($titre) > 15){
		    $suite = '...';
		}
		$ret .= '&nbsp;&nbsp;<a href="blog.php?action=cat&id=' .$cat->getAttr('id_categ'). '&site=' . $_GET['site'] . '">' .substr($titre,0,15). $suite . '</a><br/>';
	    }
	}
	return $ret;
    }

    /**
     * M�thode permettant d'afficher la liste des titres des billets
     */
    public function listeTitresBillets($billets){
	if(count($billets)>0)
	    $site = $billets[0]->getAttr('nom_site');
	else
	    $site = $_GET['site'];
	$ret = '<a href="blog.php?action=list&site=' . $site . '"><strong>Tous les billets</strong></a><br/>';
	foreach($billets as $billet){
	    $suite = '';
	    if (strlen($billet->getAttr('titre_billet')) > 15){
		$suite = '...';
	    }
	    $ret .= '&nbsp;&nbsp;<a href="blog.php?action=detail&id=' .$billet->getAttr('id_billet'). '&site=' . $site . '">' .substr($billet->getAttr('titre_billet'),0,15). $suite . '</a><br/>';
	}
	return $ret;
    }


}
?>
