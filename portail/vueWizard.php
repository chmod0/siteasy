<?php

require_once("../sites/SiteCateg.php");
require_once("../sites/Site.php");
require_once("../modele/Modele.php");
require_once("../design/design.php");
require_once("../bdd/Base.php");
session_start();

class vueWizard
{
    public static function header($mail)
    {
	$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml">
	    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <title>EasyWeb, le portail simplifié de création de sites Web</title>
	    <link rel="stylesheet" type="text/css" href="css/style.css" />
	    <script type="text/javascript" src="js/fonctionsWizard.js"></script>
<script type="text/javascript" src="js/fonctionsAjax.js"></script>
<!-- POPUP -->
<link type="text/css" media="screen" rel="stylesheet" href="../editor/css/colorbox.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="../editor/js/jquery.colorbox.js"></script>
<script type="text/javascript">
$(document).ready(function(){

    $(".modifInfos").colorbox({width:"370px", inline:true, href:"#infos"});

    });
			</script>
		<!-- /POPUP -->
		</head>
		<body>

		<!-- HEADER -->
		<a href="index.php"><div id="header"></div></a>';

	$html .= '<div id="top">';
	$html .= '<p>Bonjour <strong>' . $mail . '</strong> - <a href="index.php?action=logout">Déconnexion</a></p>';
	$html .= '<table><tr>
				<td><a href="#">Qui sommes nous</a></td>
				<td><a href="contact.php">Nous contacter</a></td>
				<td><a href="#" onClick = "javascript:afficheImage();">Images</a></td>
				<tr></table>
	    </div>';
	return $html;
    }

    public static function footer()
    {
	$html = '<div id="footer">
		    <p>Projet Tutoré - DUT Informatique - IUT Nancy-Charlemagne<br/>
		    Julien Guepin, Geoffrey Tisserand, Arnaud Lahaxe, Baptiste Kostrzewa ou pas</p>
		</div>	
		</body>
		</html>';
	return $html;
    }

    public static function afficheWizard($mail, $etape)
    {
	$html = vueWizard::header($mail);
	switch($etape)
	{

	case 0:
	    $html .= vueWizard::etape0();
	    break;

	case 1:
	    $html .= vueWizard::etape1();
	    break;
	case 2:
		$site = unserialize($_SESSION['site']);
		$nom = $site->getAttr('nom_site');
		$correct = preg_match("#^[a-zA-Z0-9_-]+$#", $nom);
		if (!$correct || Site::findByNomSite($nom) != null){
			if (Site::findByNomSite($nom) != null){
				echo '<script>alert("Nom déjà utilisé")</script>';
			}else{
				echo '<script>alert("Nom invalide")</script>';

			}
			$html .= vueWizard::etape1();
		}else{
			$html .= vueWizard::etape2();
		}
	    break;
	case 3:
	    $html .= vueWizard::etape3();
	    break;
	case 4:
	    $html .= vueWizard::etape4();
	    break;
	default:
	    $html .= vueWizard::etape1();
	    break;
	}
	$html .= vueWizard::footer();

	echo $html; 
    }


	 public static function etape0()
    {
	if($siteExiste = isset($_SESSION['site']))
	{
	    $site = unserialize($_SESSION['site']);
	}
	$html = 
	    '<div id="center">
			<div style="text-align:right; font-size:0.7em"><a href="index.php?action=cancelSite">Annuler l\'installation</a></div>
			<h3>Installation</h3>
			<p><div id="details">Bienvenue sur l\'assistant d\'installation ! Vous êtes sur le point de créer un nouveau site.
			<br/><br/>Vous pouvez à tout moment revenir en arrière et passer à la page suivante en cliquant sur les boutons situés en bas de la page.
			<br/>Vous pouvez également annuler à tout momment en cliquant sur "annuler l\'installation" en haut de la page.
			<br/><br/>Nous vous rappelons que le contenu de votre site doit respecter les règles décrites dans les <a class="modifInfos" href="#">conditions d\'utilisation</a>.</div></p>
			<div style="text-align:right">
				<a href="wizard.php?goto=1&etape=0">
				<img border="none" src="img/start.png" alt="installer le site" onmouseout="this.src=\'img/start.png\';" onmouseover="this.src=\'img/start_over.png\';" name="installSite" id="installSite" value="installation" />
				</a>
			</div>
			<!-- This contains the hidden content for inline calls -->
		<div style="display:none" >
			<div id="infos" style="padding:10px; background:#fff;color:#000000">
			<h3>Conditions Générales d\'utilisation</h3>
			</div>
		</div>
	    </div>';
	return $html;
    }


    public static function etape1()
    {
	if($siteExiste = isset($_SESSION['site']))
	{
	    $site = unserialize($_SESSION['site']);
	}
	$html = 
	    '<div id="center">
	    <div style="text-align:right; font-size:0.7em"><a href="index.php?action=cancelSite">Annuler l\'installation</a></div>
	    <h3>Informations générales</h3>
	    <p><div id="details">Indiquez ici les informations générales sur le site que vous souhaitez créer.</div></p>
	    <form method="post" id="formInfos" action="wizard.php?goto=2&etape=1">

		<div style="float:left">
			<label for="nom_site">Nom *</label><br />
			<input type="text" name="nom_site" id="nom_site" value="' . (($siteExiste) ? $site->getAttr('nom_site') : "") . '"  /><br />

			<div id="pathSite"></div><br />

			<label for="titre_site">Titre</label><br />
			<input type="text" name="titre_site" id="titre_site" value="' . (($siteExiste) ? $site->getAttr('titre_site') : "") . '" /><br /><br />
		</div>

		<div style="float:right">
			<label for="mots_cle">Mots clé</label><br />
			<input type="text" id="mots_cle" name="mots_cle" value="' . (($siteExiste) ? $site->getAttr('mots_cle') : "") . ' "/><br /><br />
			<label for="categ_site">Catégorie</label><br />
			<select type="text" id="categ_site" name="categ_site" value="' . (($siteExiste) ? $site->getAttr('categ_site') : "") . ' ">
			<option id="aucune">aucune</option>';

		$categs_site = SiteCateg::findAll();
		foreach($categs_site as $categ_site){
			$html .= '<option id="'.$categ_site->getAttr('titre_site_categ').'">' .$categ_site->getAttr('titre_site_categ'). '</option>';
		}
		$html .= '</select><br /><br />		
		</div>

		<div style="clear:both"></div>
	    <label for="desc_site">Description</label><br />
	    <textarea id="desc_site" cols="43" rows="3" name="desc_site">' . (($siteExiste) ? $site->getAttr('desc_site') : "") . '</textarea><br /><br />
		<div style="text-align:right; font-size:0.7em">* : champs obligatoires</div><br/>';
	$html .= vueWizard::boutons(1);
	$html .= 
	    '</form>

	    </div>';
	return $html;
    }

    public static function etape2()
    {
	if($siteExiste = isset($_SESSION['site']))
	{
	    $site = unserialize($_SESSION['site']);
	}
	$html = 
	    '<div id="center">
	    <div style="text-align:right; font-size:0.7em"><a href="index.php?action=cancelSite">Annuler l\'installation</a></div>
	    <h3>Modèle du site</h3>
	    <p><div id="details">Indiquez ici le modèle du site que vous souhaitez créer. Nous vous proposons plusieurs modèles : </div></p>
	    <form method="post" action="wizard.php?goto=3&etape=2">
		';
	$modeles = Modele::findAll();
	$i = 1;
	foreach($modeles as $mod){

		$libelle = $mod->getAttr('libelle_modele');
		$id = $mod->getAttr('id_modele');
		$desc = $mod->getAttr('desc_modele');
		if ($i == 1){

			$html .='<input type="radio" name="id_modele" value="'.$id.'" id="'.$id.'"checked/> <label for="'.$id.'">'.$libelle.'</label>
					<div id="details">'.$desc.'</div><br/>';
			$i = 0;
		}else{
			$html .='<input type="radio" name="id_modele" value="'.$id.'" id="'.$id.'"/> <label for="'.$id.'">'.$libelle.'</label>
					<div id="details">'.$desc.'</div><br/>';
		}
	}
	$html .= vueWizard::boutons(2);
	$html .= 
		'</form>
		</div>';
	return $html;

    }

    public static function etape3()
    {
	$html = 
	    '<div id="center">
	    <div style="text-align:right; font-size:0.7em"><a href="index.php?action=cancelSite">Annuler l\'installation</a></div>
	    <h3>Design du site</h3>
	    <p id="details">Indiquez ici le design du site que vous souhaitez créer.</p>
	    <form method="post" action="wizard.php?goto=4&etape=3">';

	$site = unserialize($_SESSION['site']);
	$id_mod = $site->getAttr('id_modele'); 
	$designs = design::findByIdModele($id_mod);
	$i = 1;
	foreach($designs as $des){
		$id_design = $des->getAttr('id_design');
		$path_design = $des->getAttr('path_design');
		$lib_design = $des->getAttr('libelle_design');
		if ($i==1){
			$html .='<label for="'.$id_design.'"><img src="..'.$path_design.'preview.png"/></label><br />
			<input type="radio" name="id_design" id="'.$id_design.'" value="'.$id_design.'"checked/>'.$lib_design.'<br /><br />';	
			$i = 0;
		}else{
			$html .='<label for="'.$id_design.'"><img src="..'.$path_design.'preview.png"/></label><br />
			<input type="radio" name="id_design" id="'.$id_design.'" value="'.$id_design.'"/>'.$lib_design.'<br /><br />';	
		}
	}	

	$html .= vueWizard::boutons(3);
	$html .= 
	    '</form>
	    </div>';
	return $html;
    }

    public static function etape4()
    {
	$html = 
	    '<div id="center">
	    <div style="text-align:right; font-size:0.7em"><a href="index.php?action=cancelSite">Annuler l\'installation</a></div>
	    <h3>Résumé des informations</h3>';

	$site = unserialize($_SESSION['site']);
	$mod =  Modele::findById($site->getAttr('id_modele'));
	$des =  design::findById($site->getAttr('id_design'));
	$html .= '<div id="details">Voici le résumé des informations du site que vous avez demandé d\'installer : <ul>
	    <li>Nom du site : <strong>' . $site->getAttr('nom_site') . '</strong></li>
	    <li>Titre : <strong>' . $site->getAttr('titre_site') . '</strong></li>
	    <li>Description : <strong>' . $site->getAttr('desc_site') . '</strong></li>
	    <li>Mots clé : <strong>' . $site->getAttr('mots_cle') . '</strong></li>
	    <li>Catégorie : <strong>' . $site->getAttr('categ_site') . '</strong></li>
	    <li>Modèle : <strong>' . $mod->getAttr('libelle_modele') . '</strong></li>
		<li>Design : <strong>' . $des->getAttr('libelle_design') . '</strong></li>
	    </ul></div><br/>';
	$html .= vueWizard::boutons(4);
	$html .= 
	    '</form>
	    <div style="text-align:center">
			<a href="index.php?action=addSite">
			<img border="none" src="img/install.png" alt="installer le site" onmouseout="this.src=\'img/install.png\';" onmouseover="this.src=\'img/install_over.png\';" name="installSite" id="installSite" value="installation" />
			</a>
		</div>
	    </div>';
	return $html;
    }

    public static function boutons($etape)
    {
	$html = "";
	switch($etape)
	{
	case 0:
	    $html .= '<input type="image" name="img_envoi" src="img/start.png" style="float:right;" >';
	    break;
	case 1:
	    $html .= '<span>Etape '.$etape.'/4 </span><input id="envoi-infos" type="image" name="img_envoi" src="img/next.png" style="float:right;" >';
	    break;
	case 4:
	    $html .= '<a href="wizard.php?goto=3"><input type="image" name="img_envoi" src="img/previous.png" style="float:left;" ></a><span>Etape '.$etape.'/4 </span>';
	    break;
	default:
	    $html .= '<a href="wizard.php?goto=' . ($etape - 1) . '"><input type="image" name="img_envoi" src="img/previous.png" style="float:left;" ></a> <span type="text-align:center">Etape '.$etape.'/4 </span>
	    <input type="image" name="img_envoi" src="img/next.png" style="float:right;" >';
	    break;
	}
	$html .= '<div style="clear:both;"></div>';
	return $html;
    }

}
?>
