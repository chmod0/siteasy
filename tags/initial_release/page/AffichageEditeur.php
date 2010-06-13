<?php

require_once '../sites/Site.php';
require_once '../sites/SiteCateg.php';
require_once '../design/design.php';

class AffichageEditeur{

	static function affichePage($content,$menu,$design,$cote=null,$page_site,$num_page,$nom_site, $titre_site, $image=null){

		echo AffichageEditeur::header($num_page, $titre_site, $design);
                $image = controller::getTabImagePage($num_page,true);
		echo "
			$cote
			<div id=\"global\">
			<div id=\"entete\">
			<h1>

			</h1>
			<p class=\"sous-titre\">
			</p>

			<h1><b><a href=\"index.php?site=$nom_site\">$titre_site</a></b></h1>

			</div><!-- #entete -->
			
			<div id=\"navigation\">

			$menu

			</div><!-- #navigation -->

			<div id=\"centre\">

			<div id=\"principal\">
			<a href=\"#\" id=\"supprPageButton\" title=\"Supprimer la page\"><img id=\"boutonSuppr\" src=\"../editor/img/icon_close.png\" onmouseout=\"this.src='../editor/img/icon_close.png'\" onmouseover=\"this.src='../editor/img/icon_close_over.png'\" /></a>
                        $image
			$content

			<div id= \"pagesite\">
			$page_site
			</div>
			<br/><br/>

			</div><!-- #principal -->
			";
		echo AffichageEditeur::footer();

	}

	static function header($num_page, $titre_site, $design)
	{
		if($num_page != null)
		{
			$script = '<script src="../editor/savePage.js"></script>';
		}
		else
		{
			$script = '<script src="../editor/newPage.js"></script>';
		}
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

			<title>' . $titre_site . '</title>

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

		<!-- on intégre le script de l\'éditeur et de l\'enregistreur -->
		<script src="../editor/editorPage.js"></script>
		<script src="../editor/fonctionsAjax.js"></script>
               
		' . $script . '
                <script type="text/javascript" src="../image/js/prototype.js"></script>
                <script type="text/javascript" src="../image/js/scriptaculous.js?load=effects,builder"></script>
                <script type="text/javascript" src="../image/js/lightbox.js"></script>
		<link rel="stylesheet" type="text/css" href="../design/page/base.css" media="all" />
		<link rel="stylesheet" href="..' . $design . 'style.css" media="screen"/>
		<link rel="stylesheet" href="../editor/goog/css/button.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/menus.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/toolbar.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/colormenubutton.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/palette.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/colorpalette.css" /> 
		<link rel="stylesheet" href="../editor/goog/css/editortoolbar.css" /> 

		<link rel="stylesheet" href="../editor/css/dialog.css" /> 
		<link rel="stylesheet" href="../editor/css/editor.css" />
                <link rel="stylesheet" href="../image/css/lightbox.css" type="text/css"  />

                

		</head>';
    if($num_page != null)
    {
	$html .= '<body onload="initFields(' . $num_page . ');">';
    }
    else
    {
	$html .= '<body onload="initFields(\'\');">';
    }
    $html .= ' 
	    <div id="editorBanner">
			<table><tr>
			<td>
			<a href="moteur.php?site=' . $_GET['site'] . '&action=new"><img border="none" src="../editor/img/addPage.png" alt="Ajouter un billet" onmouseout="this.src=\'../editor/img/addPage.png\';"  onmouseover="this.src=\'../editor/img/addPage_over.png\';" name="addPage" id="addPage" value="Ajouter une page" /></a>
			</td>
			<td>
			<a href="moteur.php?action=lien&num=' . $num_page . '"><img border="none" src="../editor/img/addLink.png" alt="Ajouter un billet" onmouseout="this.src=\'../editor/img/addLink.png\';"  onmouseover="this.src=\'../editor/img/addLink_over.png\';" name="addLink" id="addLink" value="Ajouter un lien" /></a>
			</td>
			<td>
			<a href="#" onclick="'. "showImageDialog();".  '"> <img border="none" src="../editor/img/addImage.png" alt="Quitter l\'&eacute;diteur" onmouseout="this.src=\'../editor/img/addImage.png\';"  onmouseover="this.src=\'../editor/img/addImage_over.png\';" name="setInfos" id="setInfos" value="Quitter l\'&eacute;diteur" /></a>
			</td>
			<td>
			<a class=\'modifInfos\' href="#" onclick="showInfosDialog();"><img border="none" src="../editor/img/setting.png" alt="Modifier les informations générales du site" onmouseout="this.src=\'../editor/img/setting.png\';"  onmouseover="this.src=\'../editor/img/setting_over.png\';" name="setInfos" id="setInfos" value="Modifier les informations générales du site" /></a>
			</td>
			<td>
			<a href="moteur.php?action=uneditMode&site=' . $_GET['site'] . '"><img border="none" src="../editor/img/exit.png" alt="Quitter l\'&eacute;diteur" onmouseout="this.src=\'../editor/img/exit.png\';"  onmouseover="this.src=\'../editor/img/exit_over.png\';" name="setInfos" id="setInfos" value="Quitter l\'&eacute;diteur" /></a>
			</td>
			</tr>
			</table>
			<div id="outils" style="width:50px;"></div>

			</div>
			<br />
			<br />
                        <input type="hidden" id="deb" value="0" />
                        <input type="hidden" id="num_page" value="' . $num_page . '" />
			';
    return $html;
    }


    static function footer()
    {
	    /**
	     * 
	     * On charge ici toutes les données du site
	     * elle sont ensuite chargées dans un formulaire dans une zone non affichée
	     * le Dialog chargera ce formulaire et pourra modifier ses valeurs
	     *
	     */
	$site = Site::findByNomSite($_GET['site']);
	$tab_categs = SiteCateg::findAll();
	$tab_designs = design::findByIdModele(2); 
	if($site != null)
	{
	    $titre = $site->getAttr("titre_site");
	    $categ = $site->getAttr("categ_site");
	    $mots_cle = $site->getAttr("mots_cle");
	    $descr_site = $site->getAttr("desc_site");
	    $design_site = $site->getAttr("id_design");

	    $html .= '<!-- Contenu du Dialog -->
		<div style="display:none">
		<div id="infos">
		<form id="form-modif">
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

		<label for="modif_design_site">Design : </label>
		<br />
		<select id="modif_design_site">';
	    foreach($tab_designs as $des)
	    {
		    $id_des = $des->getAttr('id_design');
		    $lib_des = $des->getAttr('libelle_design');
		    $html .= '<option id="' . $id_des . '"' . (($design_site == $id_des) ? ' selected="selected"' : '') . '>' . $lib_des . '</option>';
	    }
	    $html .= '</select>
		<br />
		<label for="modif_mots_cle_site">Mots cl&eacute;s : </label>
		<br/>	
		<input type="text" id="modif_mots_cle_site" value="' . $mots_cle . '" />

		<br/>

		<label for="modif_description_site">Description : </label>
		<br/>
		<textarea id="modif_description_site" rows="4" cols="35">' . $descr_site . '</textarea>

		<input type="hidden" value="'.$_GET['site'].'" id="nomSite" /><br />
		</form>
		</div>
		</div>';
	}

	$html .= "<div id =\"copyright\">	
	    <a href=\"../portail/index.php\">Portail de cr&eacute;ation EasyWeb</a>

	    </div>

	    </div><!-- #centre -->

	    </div><!-- #global -->

	    <script src=\"../editor/fonctionsDialog.js\"></script>
            <script type=\"text/javascript\" src=\"../portail/js/json.js\"></script>
	    </body>
	    </html>";

	return $html;
    }
}
?>
