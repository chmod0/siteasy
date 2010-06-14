<?php
require_once '../bdd/Base.php';
require_once 'model/Billet.php';
require_once 'model/Categorie.php';
require_once 'model/Commentaire.php';
require_once '../sites/Site.php';
require_once '../sites/SiteCateg.php';
require_once '../design/design.php';

/**
 * Classe permettant l'affichage de l'�diteur d'un billet
 */
class AffichageEditeur{
    /**
     * M�thode pour afficher toute une page,
     * Elle prend en param�tre le bloc du centre, le menu de gauche et celui de droite
     */
    public function affichePage($content, $menuleft, $menuright, $id) 
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
	$html = AffichageEditeur::header($id, $titre, $path_design);
	$html .= '
	    <div id="entete">
	    <h1><a href="blog.php?site=' . $_GET['site'] . '">' . $titre . '</a></h1> </div>

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
	    '. AffichageEditeur::footer() .'

	    <div id="basdepage"><a href="../portail/index.php">Portail de cr&eacute;ation EasyWeb</a></div>
	    </body>
	    </html>
	    ';
	echo $html;
    }

    /**
     * M�thode permettant d'afficher le billet pass� en param�tre
     */
    public function unBillet($billet,$categories, $idCatCourante)
    {
	$liste = '<select type="text" id="categ_billet" onchange="afficheSauvCate();"><option id="aucune">Aucune</option>';
	if($categories != null)
	{
	    foreach($categories as $cat)
	    {
		$id = $cat->getAttr('id_categ');
		$titre = $cat->getAttr('titre_categ');
		$liste .= '<option id="'.$id.'"' . (($id == $idCatCourante) ? ' selected="selected"' : '') . '>'.$titre.'</option>';

	    }
	}
	$id = $billet->getAttr('id_billet');
	$idi= $billet->getAttr('image');
	if ($idi!=0){
	    $image=image::findById($idi);
	    $titre = $image->getAttr('nom_image');
	    $nom_dur = $idi;
	    $nom_dur .= '.'.substr($titre, -3);
	    $url_image = '../image/image/'.$nom_dur;
	    $im .= "&nbsp;<img src=\"$url_image\"  height=\"200\" width=\"200\" style=\"cursor:url('../editor/img/icon_close.png');\" onClick = 'javascript:dialogSupImageBlog(".$id.");' />&nbsp;";
	}

	$liste .= '</select>';
	$ret = '<div id="billet">
	    <a href="#" id="supprBilletButton" title="Supprimer le billet"><img id="boutonSuppr" src="../editor/img/icon_close.png" onmouseout="this.src=\'../editor/img/icon_close.png\'" onmouseover="this.src=\'../editor/img/icon_close_over.png\'" /></a>
	    <div class="titre" id="titre">' .$billet->getAttr('titre_billet'). '</div>
	    <input type="button" id="saveTitreButton" value="Enregistrer" disabled="true" style="visibility:hidden;" />
	    <input type="button" id="cancelTitreButton" value="Annuler" style="visibility:hidden;" />

	    <center>'.$im.'</center>


	    <div id="contenu">' .$billet->getAttr('contenu_billet'). '</div>
	    </div>
	    <input type="button" id="saveContenuButton" value="Enregistrer" disabled="true" style="visibility:hidden;" />
	    <input type="button" id="cancelContenuButton" value="Annuler" style="visibility:hidden;" />
	    <br />
	    Cat&eacute;gorie : 
	    '.$liste.'

	    <input type="button" id="saveCate" value="Enregistrer" onClick = "saveCate()"  style="visibility:hidden;" />
	    <input type="button" id="annulCate" value="Annuler" onClick = "cacherSauvCate()" style="visibility:hidden;" />


	    <br/><div style="text-align:right"><em>Post&eacute; le '.substr($billet->getAttr('date_billet'),8,2). '/' .substr($billet->getAttr('date_billet'),5,2). '/' .substr($billet->getAttr('date_billet'),0,4).' &agrave; '.substr($billet->getAttr('date_billet'),10,20).'</em></div><br/>';

	return $ret;
    }

    /**
     * Méthode permettant d'afficher une liste de billets passée en paramètre
     */
    public function listeBillets($billets, $cat=null){
	$ret = '';
	if($billets != null)
	    $site = $billets[0]->getAttr('nom_site');
	if ($cat != null){
	    $ret .= '<div id="idcat" style="visibility:hidden">' . $cat->getAttr("id_categ") . '</div>
		<div id="namecat">' . $cat->getAttr('titre_categ') . '</div>
		<div id="descat">' . $cat->getAttr('libelle_categ') .'</div>
		<div align="right"><a href="#" title="Supprimer la catégorie" id="supprCategButton" onclick="supprimerCategorie();"><img id="boutonSupprCateg" src="../editor/img/icon_close.png" onmouseout="this.src=\'../editor/img/icon_close.png\'" onmouseover="this.src=\'../editor/img/icon_close_over.png\'" /></a></div>
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
     * Dans le footer, on �crit le code html des formulaires de modifications des infos, transparents pour l'utilisateur
     * il est charg� dans un Dialog par la suite avec javascript
     *
     * */
    public static function footer()
    {
	$site = Site::findByNomSite($_GET['site']);
	$tab_categs = SiteCateg::findAll();
	$tab_designs = design::findByIdModele(1); 
	if($site != null)
	{
	    $titre = $site->getAttr("titre_site");
	    $categ = $site->getAttr("categ_site");
	    $mots_cle = $site->getAttr("mots_cle");
	    $descr_site = $site->getAttr("desc_site");
	    $design_site = $site->getAttr("id_design");

	    $html = '<!-- This contains the hidden content for inline calls -->
		<div style="display:none">
		<div id="infos">
		<label for="modif_titre_site">Titre : </label>
		<br/>
		<input type="text" id="modif_titre_site" value="' . $titre . '" />

		<br/>

		<label for="modif_categ_site">Cat&eacute;gorie : </label>
		<br/>
		<select id="modif_categ_site">';
	    $html .= '<option' . (($categ == "aucune") ? ' selected="selected"' : '') . '>aucune</option>';
	    foreach($tab_categs as $cat)
	    {
		$titre_cat = $cat->getAttr('titre_site_categ');
		$html .= '<option' . (($titre_cat == $categ) ? ' selected="selected"' : '') . '>' . $titre_cat . '</option>';
	    }
	    $html .= '</select>
		<br />
		<label for="modif_design_site" >Design : </label>
		<br />
		<select id="modif_design_site">';
	    foreach($tab_designs as $des)
	    {
		$id_des = $des->getAttr('id_design');
		$lib_des = $des->getAttr('libelle_design');
		$html .= '<option id="' . $id_des . '"' . (($design_site == $id_des) ? ' selected="selected"' : '') . '>' . $lib_des . '</option>';
	    }
	    $html .= '</select>
		<br/>	






		<label for="modif_mots_cle_site">Mots cl&eacute;s : </label>
		<br/>	
		<input type="text" id="modif_mots_cle_site" value="' . $mots_cle . '" />

		<br/>

		<label for="modif_description_site">Description : </label>
		<br/>
		<textarea id="modif_description_site" rows="4" cols="35">' . $descr_site . '</textarea>
		<input type="hidden" value="'.$_GET['site'].'" id="nomSite" /><br />
		</div>
		</div>';

	    // on �crit le formulaire d'ajout d'une cat�gorie, qui sera affich� dans un Dialog
	    // �galement transparent par d�faut
	    $html .= '
		<div style="display:none">
		<div id="categ">
		<label for="insert_titre_categ">Titre de la cat&eacute;gorie : </label>
		<br />
		<input type="text" id="insert_titre_categ" />
		<br />

		<label for="insert_libelle_categ">Libell&eacute; de la cat&eacute;gorie : </label>
		<br />
		<input type="text" id="insert_libelle_categ">

		<input type="hidden" id="nomSite" value="' . $_GET['site'] . '" />
		</div>
		</div>';
	}

	return $html;
    }

    public static function header($id, $titre, $path_design)
    {
	if($id != null)
	{
	    $script = '<script src="../editor/saveBlog.js"></script>';
	}
	else
	{
	    $script = '<script src="../editor/newBlog.js"></script>';
	}
	$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml">
	    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	    <title>' . $titre . '</title>

	    <script src="../editor/goog/base.js"></script>
<script>
goog.require("goog.dom");
goog.require("goog.editor.SeamlessField");
goog.require("goog.editor.Command");
goog.require("goog.editor.plugins.BasicTextFormatter");
goog.require("goog.editor.plugins.EnterHandler");
goog.require("goog.editor.plugins.HeaderFormatter");
goog.require("goog.editor.plugins.ListTabHandler");
goog.require("goog.editor.plugins.LoremIpsum");
goog.require("goog.editor.plugins.RemoveFormatting");
goog.require("goog.editor.plugins.SpacesTabHandler");
goog.require("goog.editor.plugins.UndoRedo");
goog.require("goog.ui.editor.DefaultToolbar");
goog.require("goog.ui.editor.ToolbarController");
goog.require("goog.ui.Dialog");

		</script>

		<!-- on int�gre le script de l\'�diteur et de l\'enregistreur -->

	    <script type="text/javascript" src="../portail/js/json.js"></script>
		<script src="../editor/editorBlog.js"></script>
		<script src="../editor/fonctionsDialog.js"></script>
		<script src="controller/fonctionsDialogCategorie.js"></script>
		<script src="../editor/fonctionsAjax.js"></script>
		' . $script . '

		<link rel="stylesheet" href="../' . $path_design . 'style.css" />
		<link rel="stylesheet" href="../editor/goog/css/button.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/menus.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/toolbar.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/colormenubutton.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/palette.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/colorpalette.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/editortoolbar.css" /> 

		<link rel="stylesheet" href="../editor/css/editor.css" />
		<link rel="stylesheet" href="../editor/css/dialog.css" /> 


	</head>';
    if($id != null)
    {
	$html .= '<body onload="initFields(' . $id . ');">';
    }
    else
    {
	$html .= '<body onload="initFields(\'\');">';
    }
    $html .= ' 
	    <div id="editorBanner">
			<table><tr>
			<td>
			<a href="blog.php?site=' . $_GET['site'] . '&action=new"><img border="none" src="../editor/img/addBillet.png" alt="Ajouter un billet" onmouseout="this.src=\'../editor/img/addBillet.png\';"  onmouseover="this.src=\'../editor/img/addBillet_over.png\';" name="addBillet" id="addBillet" value="Ajouter un billet" /></a>
			</td>
			<td>
			<a href="#" onclick="showCategDialog();"><img border="none" src="../editor/img/addCateg.png" alt="Ajouter un billet" onmouseout="this.src=\'../editor/img/addCateg.png\';"  onmouseover="this.src=\'../editor/img/addCateg_over.png\';" name="addCateg" id="addCateg" value="Ajouter une cat�gorie" /></a>
			</td>';

    if(strcmp($_GET['action'],'detail')==0)
    {
			$html.=' <td>
			<a href="#" onclick="'. "showImageDialogBlog();".  '"> <img border="none" src="../editor/img/addImage.png" alt="Quitter l\'&eacute;diteur" onmouseout="this.src=\'../editor/img/addImage.png\';"  onmouseover="this.src=\'../editor/img/addImage_over.png\';" name="setInfos" id="setInfos" value="Quitter l\'&eacute;diteur" /></a>
			</td>';
    }

    $html .= '		<td>
			<a href="#" onclick="showInfosDialog();"><img border="none" src="../editor/img/setting.png" alt="Modifier les informations g�n�rales du site" onmouseout="this.src=\'../editor/img/setting.png\';"  onmouseover="this.src=\'../editor/img/setting_over.png\';" name="setInfos" id="setInfos" value="Modifier les informations g�n�rales du site" /></a>

			</td>';

			$html.='<td>
			<a href="blog.php?action=unedit&site=' . $_GET['site'] . '"><img border="none" src="../editor/img/exit.png" alt="Quitter l\'&eacute;diteur" onmouseout="this.src=\'../editor/img/exit.png\';"  onmouseover="this.src=\'../editor/img/exit_over.png\';" name="setInfos" id="setInfos" value="Quitter l\'&eacute;diteur" /></a>
			</td>
			</tr>
			</table>
			<div id="outils" style="width:50px;"></div>

			</div>
			<input type="hidden" id="deb" value="0" />
			<input type="hidden" id="id" value="' . $id . '" />
			<br />
			';
    return $html;
    }

}
?>
