<?php
session_start();
include '../user/User.php';
class Vue
{
    public static function header($script)
    {
	$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	    <html xmlns="http://www.w3.org/1999/xhtml">
	    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	    <title>EasyWeb, le portail simplifié de création de sites Web</title>
	    <link rel="stylesheet" type="text/css" href="css/style.css" />
	    <link rel="stylesheet" href="css/form-field-tooltip.css" media="screen" type="text/css">

	    <script src="../editor/goog/base.js"></script>
<script>
goog.require("goog.ui.Dialog");
		</script>
		<script type="text/javascript" src="js/fonctionDialog.js"></script>
		<script type="text/javascript" src="js/rounded-corners.js"></script>
		<script type="text/javascript" src="js/form-field-tooltip.js"></script>
		<script type="text/javascript" src="js/fonctionsAjax.js"></script>
		<script type="text/javascript" src="js/json.js"></script>
		<script type="text/javascript" src="js/' . $script . '"></script>

		<link rel="stylesheet" href="../image/css/lightbox.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="../editor/css/dialog.css" /> 
		<script type="text/javascript" src="../image/js/prototype.js"></script>
		<script type="text/javascript" src="../image/js/scriptaculous.js?load=effects,builder"></script>
		<script type="text/javascript" src="../image/js/lightbox.js"></script>



	    </head>
	    <body>

<!-- HEADER -->
<a href="index.php"><div id="header"></div></a>';
	if (isset ($_GET['up'])){
	    $html .= '<script type="text/javascript">afficheImage(0)</script>';

	}
return $html;
    }

    public static function footer()
    {
	$user = user::findById($_SESSION['mail']);
	if($user != null)
	{
	    $admin = $user->getAttr('admin');
	    $conect = $_SESSION['connecte'];
	    if ($admin==1 && $conect){
		$adm = '<a href="../administration/index.php">Administration</a>';
	    }
	}
	//provisoire
	$mail =$_SESSION["mail"];
	$html = '<div id="footer">
	    <p>Projet Tutoré - DUT Informatique - IUT Nancy-Charlemagne<br/>
	    Julien Guepin, Geoffrey Tisserand, Arnaud Lahaxe, Baptiste Kostrzewa</p>
	    <center>'.$adm.'</center>
	    </div>
<script type="text/javascript">
var tooltipObj = new DHTMLgoodies_formTooltip();
tooltipObj.setTooltipPosition(\'right\');
tooltipObj.setPageBgColor(\'#EEE\');
tooltipObj.initFormFieldTooltip();
</script>
	    <input type="hidden" id="deb" value="0" />
	    </body>		
	    </html>';
	return $html;
    }	

    public static function afficheAccueil($mail)
    {
	$html = Vue::header("fonctionsPortail.js");
	$html .= '<!-- BLOC D\'EN TETE -->
	    <div id="top">

	    <!-- FORMULAIRE DE CONNEXION -->
	    <form method="post" id="formConnex">
	    <input type="text" name="login" id="login" value="' . (($mail != "") ? $mail : "Adresse e-mail") . '" />
	    <br/>
	    <span id="emailErrorMessageConnex"></span>
	    <br/>

	    <input type="password" name="pass" id="pass" value="Mot de passe" />
	    <br/>

	    <span id="passErrorMessageConnex"></span>

	    </form>

	    <!-- VALIDER CONNEXION -->
	    <div id="connex">
	    <a href="#connexion">
	    <img border="none" src="img/connecter.png" alt="se connecter" onmouseout="this.src=\'img/connecter.png\';"  onmouseover="this.src=\'img/connecter_over.png\';" name="validConnex" id="validConnex" value="SE CONNECTER" />
	    </a>

	    <br/><br/>

	    <div id="oubliPass">
	    <a href="#recuperation">mot de passe oublié ?</a>
	    </div>
	    </div>

	    <div style="clear:both"></div>
	    </div>

	    <!-- BLOC CENTRAL -->
	    <div id="center">
	    <br />
	    <div style="text-align:center; font-size:1.2em;">
	    Inscrivez-vous maintenant !
	    </div>	
	    <div style="text-align:center; color:#3d80b9 ;font-size:1em;">
	    Et commencez à créer votre site
	    </div>				
	    <br/><br/>

	    <!-- FORMULAIRE D\'INSCRIPTION -->
	    <form method="post" id="formInscr">
	    <label for="email">Adresse email</label>
	    <input type="text" name="email" id="email" tooltipText="Indiquez ici votre adresse email valide. Celle-ci vous servira plus tard pour vous connecter sur le portail" />
	    <br/><br/>
	    <div id="emailErrorMessage"></div>
	    <br/>
	    <label for="motpasse">Mot de passe</label>
	    <input type="password" name="motpasse" id="motpasse" tooltipText="Tapez ici un mot de passe d\'au moins 3 caractères." />
	    <br/><br/> 
	    <div id="passErrorMessage"></div>
	    <br/>
	    <label for="confpass">Confirmer <br/>mot de passe</label>
	    <input type="password" name="confpass" id="confpass" tooltipText="Retapez votre mot de passe, il doit être identique au précédent." />
	    <br/><br/>
	    <div id="confPassErrorMessage"></div>
	    <br/><br/>
	    <a href="#inscription"><img border="none" src="img/inscrire.png" alt="S\'inscrire" onmouseout="this.src=\'img/inscrire.png\';" onmouseover="this.src=\'img/inscrire_over.png\';" name="validInscr" id="validInscr" value="INSCRIPTION" /></a>
	    <br/>
	    </form>

	    <div style="clear:both"></div>


	    </div>';
	$html .= Vue::footer();
	echo $html;
    }

    public static function afficheProfil($mail, $array_sites)
    {
	$html = Vue::header("fonctionsProfil.js");
	$html .= '<div id="top">';
	$html .= '<p>Bonjour <strong>' . $mail . '</strong> - <a href="index.php?action=logout">Déconnexion</a></p>';
	$html .= '<table><tr>
				<td><a href="#">Qui sommes nous</a></td>
				<td><a href="contact.php">Nous contacter</a></td>
				<td><a id="btnchangeable" href="#" onClick = "javascript:afficheImage();">Images</a></td>
				<tr></table>
	    </div>';


	$html .= '<div id="center">';
	$html .= '<div style="float:left">';
	$html .= '<h3>Vos sites</h3>';

	$html .= Vue::afficheListeSites($array_sites);

	$html .= '</div>';
	$html .= '<div style="float:right">';
	$html .= '<a href="wizard.php"><img style="margin-top:20px;" border="none" src="img/add_site.png" alt="Créer un nouveau site" onmouseout="this.src=\'img/add_site.png\';"  onmouseover="this.src=\'img/add_site_over.png\';" name="addSite" id="creerSite" value="CREER UN SITE" /></a>';
	$html .= '</div>';
	$html .= '<div style="clear:both"></div>';
	$html .= '<div id="details" style="display:none"></div>';

	$html .= '<div style="margin-top:10px; text-align:center">';
	$html .= '<a id="lienModif" href="#" target="_blank"><img style="margin-right:20px;visibility:hidden" border="none" src="img/modifier.png" alt="Modifier le site" onmouseout="this.src=\'img/modifier.png\';"  onmouseover="this.src=\'img/modifier_over.png\';" name="modifySite" id="boutonModif" value="Modifier le site" /></a>';
    $html .= '<a id="lienVisit" href="#" target="_blank"><img style="margin-right:20px;visibility:hidden" border="none" src="img/voir.png" alt="Modifier le site" onmouseout="this.src=\'img/voir.png\';"  onmouseover="this.src=\'img/voir_over.png\';" name="visitSite" id="boutonVisit" value="Modifier le site" /></a>';
	$html .= '<a id="lienDelete" href="#"><img style="visibility:hidden" border="none" src="img/supprimer.png" alt="Supprimer le site" onmouseout="this.src=\'img/supprimer.png\';"  onmouseover="this.src=\'img/supprimer_over.png\';" name="deleteSite" id="boutonDelete" value="Supprimer le site" /></a>';
    $html .= '</div>';

	$html .= '</div>';
	$html .= Vue::footer();	
	echo $html;
    }

    public static function afficheListeSites($array_sites)
    {
	$chaine = "";
	if($array_sites != null)
	{
	    $chaine .= '<ul>';
	    foreach($array_sites as $site)
	    {
		$nom_site = $site->getAttr('nom_site');

		$id_mod = $site->getAttr('id_modele');
		if ($id_mod==1){
		    $visite = "../index.php/blog/$nom_site";
		}else{
		    $visite = "../index.php/page/$nom_site";
		}


		$chaine .= '<li><a href="#" name="nomsite" id="' . $nom_site . '" onclick="detailsSite(\'' . $nom_site . '\');">' . $nom_site . '</a> </li>';
	    }
	    $chaine .= '</ul>';
	}
	return $chaine;
    }

    public static function recuperation($mail,$id){


	$html = Vue::header("fonctionsProfil.js");

	$html .= '<div id="top">';

	$html .= '<p>Recuperation de mot de passe : <strong>' . $mail . '</strong></p>';
	$html .= '<table><tr><td><a href="index.php?action=logout">Quitter</a></td><td><a href="#">Documentation</a></td><td><a href="contact.php">Nous contacter</a></td><tr></table>
	    </div>';
	$html .= "<div id=\"center\">";
	$html .="<form method=\"post\" action = '../recuperation/controleur_recuperation.php?mail=$mail&id=$id' >";
	$html.="<br/><br/>Mot de passe
	    <input type=\"password\" name=\"motpasse\" /></input>
	    <br/><br/> 
	    Confirmer <br/>mot de passe
	    <input type=\"password\" name=\"confpass\" /></input>
	    <br/><br/>
	    <br/><br/>";
	$html .= '<input type="submit" value = \'Valider\'></input>';
	$html .= '</div>';
	$html .= Vue::footer();	
	return $html;

    }


}
?>
